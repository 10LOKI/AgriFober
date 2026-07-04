import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../core/api_exception.dart';
import 'parcel_repository.dart';

class ParcelDetailScreen extends ConsumerWidget {
  const ParcelDetailScreen({super.key, required this.parcelId});
  final int parcelId;

  Future<void> _confirmDelete(BuildContext context, WidgetRef ref) async {
    final confirmed = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Delete parcel?'),
        content: const Text('This cannot be undone.'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(ctx, false),
            child: const Text('Cancel'),
          ),
          FilledButton(
            onPressed: () => Navigator.pop(ctx, true),
            style: FilledButton.styleFrom(backgroundColor: Colors.red),
            child: const Text('Delete'),
          ),
        ],
      ),
    );
    if (confirmed != true || !context.mounted) return;

    try {
      await ref.read(parcelRepositoryProvider).delete(parcelId);
      ref.invalidate(parcelsProvider);
      if (context.mounted) context.go('/parcels');
    } on ApiException catch (e) {
      if (context.mounted) {
        ScaffoldMessenger.of(context)
            .showSnackBar(SnackBar(content: Text(e.message)));
      }
    }
  }

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final async = ref.watch(parcelProvider(parcelId));

    return Scaffold(
      appBar: AppBar(
        title: Text(async.valueOrNull?.nom ?? 'Parcel'),
        actions: [
          IconButton(
            icon: const Icon(Icons.edit),
            onPressed: () => context.push('/parcels/$parcelId/edit'),
          ),
          IconButton(
            icon: const Icon(Icons.delete_outline),
            onPressed: () => _confirmDelete(context, ref),
          ),
        ],
      ),
      body: async.when(
        loading: () => const Center(child: CircularProgressIndicator()),
        error: (e, _) => Center(
          child: Padding(
            padding: const EdgeInsets.all(24),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Text('$e', textAlign: TextAlign.center),
                const SizedBox(height: 12),
                FilledButton(
                  onPressed: () => ref.invalidate(parcelProvider(parcelId)),
                  child: const Text('Retry'),
                ),
              ],
            ),
          ),
        ),
        data: (p) => RefreshIndicator(
          onRefresh: () async => ref.invalidate(parcelProvider(parcelId)),
          child: ListView(
            padding: const EdgeInsets.all(16),
            children: [
              _Row('Culture', p.culture?.nomCommun ?? '—'),
              _Row('Surface', p.surface != null ? '${p.surface} ha' : '—'),
              _Row('Status', p.status ?? '—'),
              _Row(
                  'Health score',
                  p.healthScore != null
                      ? '${p.healthScore!.toStringAsFixed(0)} / 100'
                      : '—'),
              _Row('Planted', _fmtDate(p.datePlantation)),
              _Row('Est. harvest', _fmtDate(p.dateRecolteEstimee)),
              _Row(
                  'Location',
                  (p.latitude != null && p.longitude != null)
                      ? '${p.latitude}, ${p.longitude}'
                      : '—'),
              if (p.culture?.conseils != null &&
                  p.culture!.conseils!.isNotEmpty) ...[
                const SizedBox(height: 16),
                Text('Culture advice',
                    style: Theme.of(context).textTheme.titleMedium),
                const SizedBox(height: 8),
                Card(
                  child: Padding(
                    padding: const EdgeInsets.all(16),
                    child: Text(p.culture!.conseils!),
                  ),
                ),
              ],
            ],
          ),
        ),
      ),
    );
  }

  static String _fmtDate(DateTime? d) => d == null
      ? '—'
      : '${d.year}-${d.month.toString().padLeft(2, '0')}-${d.day.toString().padLeft(2, '0')}';
}

class _Row extends StatelessWidget {
  const _Row(this.label, this.value);
  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 120,
            child: Text(label,
                style: Theme.of(context)
                    .textTheme
                    .bodyMedium
                    ?.copyWith(color: Theme.of(context).hintColor)),
          ),
          Expanded(child: Text(value)),
        ],
      ),
    );
  }
}
