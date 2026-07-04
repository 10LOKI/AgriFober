import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../models/culture.dart';
import 'catalogue_repository.dart';

class CultureDetailScreen extends ConsumerWidget {
  const CultureDetailScreen({super.key, required this.cultureId});
  final int cultureId;

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final async = ref.watch(cultureDetailProvider(cultureId));

    return Scaffold(
      appBar: AppBar(
        title: Text(async.valueOrNull?.nomCommun ?? 'Culture'),
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
                  onPressed: () =>
                      ref.invalidate(cultureDetailProvider(cultureId)),
                  child: const Text('Retry'),
                ),
              ],
            ),
          ),
        ),
        data: (c) => _CultureDetail(culture: c),
      ),
    );
  }
}

class _CultureDetail extends StatelessWidget {
  const _CultureDetail({required this.culture});
  final Culture culture;

  @override
  Widget build(BuildContext context) {
    final c = culture;
    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        if (c.nomScientifique != null)
          Text(
            c.nomScientifique!,
            style: Theme.of(context)
                .textTheme
                .bodyMedium
                ?.copyWith(fontStyle: FontStyle.italic),
          ),
        const SizedBox(height: 12),
        Wrap(
          spacing: 8,
          runSpacing: 8,
          children: [
            if (c.type != null) Chip(label: Text(c.type!)),
            if (c.saison != null) Chip(label: Text(c.saison!)),
            if (c.region != null) Chip(label: Text(c.region!)),
            if (c.soilType != null)
              Chip(label: Text('Sol: ${c.soilType}')),
          ],
        ),
        const SizedBox(height: 16),
        Card(
          child: Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text('Growing conditions',
                    style: Theme.of(context).textTheme.titleMedium),
                const SizedBox(height: 12),
                if (c.phSolMin != null || c.phSolMax != null)
                  _InfoRow(
                    icon: Icons.science_outlined,
                    label: 'Soil pH',
                    value: '${c.phSolMin ?? '—'} – ${c.phSolMax ?? '—'}',
                  ),
                if (c.tempMin != null || c.tempMax != null)
                  _InfoRow(
                    icon: Icons.thermostat,
                    label: 'Temperature',
                    value:
                        '${c.tempMin ?? '—'}°C – ${c.tempMax ?? '—'}°C',
                  ),
                if (c.besoinEauCycle != null)
                  _InfoRow(
                    icon: Icons.water_drop_outlined,
                    label: 'Water per cycle',
                    value: '${c.besoinEauCycle} mm',
                  ),
              ],
            ),
          ),
        ),
        if (c.conseils != null && c.conseils!.isNotEmpty) ...[
          const SizedBox(height: 16),
          Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text('Advice',
                      style: Theme.of(context).textTheme.titleMedium),
                  const SizedBox(height: 8),
                  Text(c.conseils!),
                ],
              ),
            ),
          ),
        ],
        if (c.products.isNotEmpty) ...[
          const SizedBox(height: 24),
          Text('Recommended products',
              style: Theme.of(context).textTheme.titleMedium),
          const SizedBox(height: 8),
          ...c.products.map(
            (p) => Card(
              child: ListTile(
                leading: const CircleAvatar(
                  child: Icon(Icons.inventory_2_outlined),
                ),
                title: Text(p.nomCommercial),
                subtitle: Text([
                  if (p.type != null) p.type!,
                  if (p.pivotDosage != null) p.pivotDosage!
                  else if (p.dosageRecommande != null) p.dosageRecommande!,
                ].join(' · ')),
                trailing: const Icon(Icons.chevron_right),
                onTap: () => context.push('/products/${p.id}'),
              ),
            ),
          ),
        ],
      ],
    );
  }
}

class _InfoRow extends StatelessWidget {
  const _InfoRow({
    required this.icon,
    required this.label,
    required this.value,
  });
  final IconData icon;
  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 6),
      child: Row(
        children: [
          Icon(icon, size: 20, color: Theme.of(context).colorScheme.primary),
          const SizedBox(width: 12),
          Expanded(child: Text(label)),
          Text(value,
              style: const TextStyle(fontWeight: FontWeight.w600)),
        ],
      ),
    );
  }
}
