import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../core/api_exception.dart';
import '../../models/parcel.dart';
import 'parcel_repository.dart';

/// Create when [parcelId] is null, edit otherwise.
/// Mirrors ParcelStoreRequest / ParcelUpdateRequest validation.
class ParcelFormScreen extends ConsumerStatefulWidget {
  const ParcelFormScreen({super.key, this.parcelId});
  final int? parcelId;

  @override
  ConsumerState<ParcelFormScreen> createState() => _ParcelFormScreenState();
}

class _ParcelFormScreenState extends ConsumerState<ParcelFormScreen> {
  final _formKey = GlobalKey<FormState>();
  final _nom = TextEditingController();
  final _surface = TextEditingController();
  final _latitude = TextEditingController();
  final _longitude = TextEditingController();
  int? _cultureId;
  String _status = 'grow';
  DateTime? _datePlantation;
  DateTime? _dateRecolte;

  bool _loading = false;
  bool _prefilled = false;
  String? _error;
  Map<String, List<String>>? _fieldErrors;

  bool get _isEdit => widget.parcelId != null;

  @override
  void dispose() {
    _nom.dispose();
    _surface.dispose();
    _latitude.dispose();
    _longitude.dispose();
    super.dispose();
  }

  void _prefill(Parcel p) {
    if (_prefilled) return;
    _prefilled = true;
    _nom.text = p.nom;
    _surface.text = p.surface?.toString() ?? '';
    _latitude.text = p.latitude?.toString() ?? '';
    _longitude.text = p.longitude?.toString() ?? '';
    _cultureId = p.culture?.id;
    _status = p.status ?? 'grow';
    _datePlantation = p.datePlantation;
    _dateRecolte = p.dateRecolteEstimee;
  }

  String? _serverError(String field) => _fieldErrors?[field]?.first;

  Map<String, dynamic> _payload() {
    return {
      if (_nom.text.trim().isNotEmpty) 'nom': _nom.text.trim(),
      'surface': double.parse(_surface.text.trim()),
      'culture_id': _cultureId,
      'status': _status,
      'date_plantation': _fmt(_datePlantation),
      'date_recolte_estimee': _fmt(_dateRecolte),
      if (_latitude.text.trim().isNotEmpty)
        'latitude': double.tryParse(_latitude.text.trim()),
      if (_longitude.text.trim().isNotEmpty)
        'longitude': double.tryParse(_longitude.text.trim()),
    };
  }

  static String? _fmt(DateTime? d) => d == null
      ? null
      : '${d.year}-${d.month.toString().padLeft(2, '0')}-${d.day.toString().padLeft(2, '0')}';

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() {
      _loading = true;
      _error = null;
      _fieldErrors = null;
    });
    try {
      final repo = ref.read(parcelRepositoryProvider);
      if (_isEdit) {
        await repo.update(widget.parcelId!, _payload());
        ref.invalidate(parcelProvider(widget.parcelId!));
      } else {
        await repo.create(_payload());
      }
      ref.invalidate(parcelsProvider);
      if (mounted) context.pop();
    } on ApiException catch (e) {
      setState(() {
        _error = e.message;
        _fieldErrors = e.errors;
      });
    } catch (_) {
      setState(() => _error = 'Something went wrong. Try again.');
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  Future<void> _pickDate({required bool plantation}) async {
    final now = DateTime.now();
    final initial =
        (plantation ? _datePlantation : _dateRecolte) ?? now;
    final picked = await showDatePicker(
      context: context,
      initialDate: initial,
      firstDate: DateTime(now.year - 5),
      lastDate: DateTime(now.year + 5),
    );
    if (picked == null) return;
    setState(() {
      if (plantation) {
        _datePlantation = picked;
      } else {
        _dateRecolte = picked;
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    final culturesAsync = ref.watch(culturesProvider);

    // In edit mode, prefill once the parcel loads.
    if (_isEdit) {
      final parcelAsync = ref.watch(parcelProvider(widget.parcelId!));
      if (parcelAsync.hasValue) _prefill(parcelAsync.value!);
      if (parcelAsync.isLoading && !_prefilled) {
        return Scaffold(
          appBar: AppBar(title: const Text('Edit parcel')),
          body: const Center(child: CircularProgressIndicator()),
        );
      }
    }

    return Scaffold(
      appBar: AppBar(title: Text(_isEdit ? 'Edit parcel' : 'New parcel')),
      body: SafeArea(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(24),
          child: Form(
            key: _formKey,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                if (_error != null) ...[
                  Container(
                    padding: const EdgeInsets.all(12),
                    decoration: BoxDecoration(
                      color: Colors.red.shade50,
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: Text(_error!,
                        style: TextStyle(color: Colors.red.shade700)),
                  ),
                  const SizedBox(height: 16),
                ],
                TextFormField(
                  controller: _nom,
                  decoration: InputDecoration(
                    labelText: 'Name (optional)',
                    errorText: _serverError('nom'),
                  ),
                ),
                const SizedBox(height: 16),
                TextFormField(
                  controller: _surface,
                  keyboardType:
                      const TextInputType.numberWithOptions(decimal: true),
                  decoration: InputDecoration(
                    labelText: 'Surface (ha)',
                    errorText: _serverError('surface'),
                  ),
                  validator: (v) {
                    final n = double.tryParse((v ?? '').trim());
                    if (n == null || n < 0.01) return 'Enter a surface ≥ 0.01';
                    return null;
                  },
                ),
                const SizedBox(height: 16),
                culturesAsync.when(
                  loading: () => const LinearProgressIndicator(),
                  error: (_, __) => const Text('Could not load cultures.'),
                  data: (cultures) => DropdownButtonFormField<int?>(
                    value: _cultureId,
                    decoration: InputDecoration(
                      labelText: 'Culture (optional)',
                      errorText: _serverError('culture_id'),
                    ),
                    items: [
                      const DropdownMenuItem<int?>(
                          value: null, child: Text('—')),
                      ...cultures.map((c) => DropdownMenuItem<int?>(
                            value: c.id,
                            child: Text(c.nomCommun),
                          )),
                    ],
                    onChanged: (v) => setState(() => _cultureId = v),
                  ),
                ),
                const SizedBox(height: 16),
                DropdownButtonFormField<String>(
                  value: _status,
                  decoration: InputDecoration(
                    labelText: 'Status',
                    errorText: _serverError('status'),
                  ),
                  items: const [
                    DropdownMenuItem(value: 'grow', child: Text('Growing')),
                    DropdownMenuItem(value: 'harvest', child: Text('Harvest')),
                    DropdownMenuItem(value: 'fallow', child: Text('Fallow')),
                  ],
                  onChanged: (v) => setState(() => _status = v ?? 'grow'),
                ),
                const SizedBox(height: 16),
                _DateField(
                  label: 'Plantation date',
                  value: _fmt(_datePlantation),
                  errorText: _serverError('date_plantation'),
                  onTap: () => _pickDate(plantation: true),
                  onClear: _datePlantation == null
                      ? null
                      : () => setState(() => _datePlantation = null),
                ),
                const SizedBox(height: 16),
                _DateField(
                  label: 'Estimated harvest date',
                  value: _fmt(_dateRecolte),
                  errorText: _serverError('date_recolte_estimee'),
                  onTap: () => _pickDate(plantation: false),
                  onClear: _dateRecolte == null
                      ? null
                      : () => setState(() => _dateRecolte = null),
                ),
                const SizedBox(height: 16),
                Row(
                  children: [
                    Expanded(
                      child: TextFormField(
                        controller: _latitude,
                        keyboardType: const TextInputType.numberWithOptions(
                            decimal: true, signed: true),
                        decoration: InputDecoration(
                          labelText: 'Latitude',
                          errorText: _serverError('latitude'),
                        ),
                        validator: _optionalRange(-90, 90),
                      ),
                    ),
                    const SizedBox(width: 12),
                    Expanded(
                      child: TextFormField(
                        controller: _longitude,
                        keyboardType: const TextInputType.numberWithOptions(
                            decimal: true, signed: true),
                        decoration: InputDecoration(
                          labelText: 'Longitude',
                          errorText: _serverError('longitude'),
                        ),
                        validator: _optionalRange(-180, 180),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 24),
                FilledButton(
                  onPressed: _loading ? null : _submit,
                  child: _loading
                      ? const SizedBox(
                          height: 20,
                          width: 20,
                          child: CircularProgressIndicator(strokeWidth: 2))
                      : Text(_isEdit ? 'Save changes' : 'Create parcel'),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  static String? Function(String?) _optionalRange(double min, double max) {
    return (v) {
      final t = (v ?? '').trim();
      if (t.isEmpty) return null;
      final n = double.tryParse(t);
      if (n == null || n < min || n > max) return 'Between $min and $max';
      return null;
    };
  }
}

class _DateField extends StatelessWidget {
  const _DateField({
    required this.label,
    required this.value,
    required this.onTap,
    this.onClear,
    this.errorText,
  });

  final String label;
  final String? value;
  final VoidCallback onTap;
  final VoidCallback? onClear;
  final String? errorText;

  @override
  Widget build(BuildContext context) {
    return InkWell(
      onTap: onTap,
      child: InputDecorator(
        decoration: InputDecoration(
          labelText: label,
          errorText: errorText,
          suffixIcon: onClear == null
              ? const Icon(Icons.calendar_today, size: 18)
              : IconButton(
                  icon: const Icon(Icons.clear, size: 18),
                  onPressed: onClear,
                ),
        ),
        child: Text(value ?? 'Not set'),
      ),
    );
  }
}
