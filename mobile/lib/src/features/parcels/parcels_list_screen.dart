import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import 'parcel_repository.dart';

class ParcelsListScreen extends ConsumerWidget {
  const ParcelsListScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final async = ref.watch(parcelsProvider);

    return Scaffold(
      appBar: AppBar(title: const Text('My parcels')),
      floatingActionButton: FloatingActionButton(
        onPressed: () => context.push('/parcels/new'),
        child: const Icon(Icons.add),
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
                  onPressed: () => ref.invalidate(parcelsProvider),
                  child: const Text('Retry'),
                ),
              ],
            ),
          ),
        ),
        data: (parcels) => RefreshIndicator(
          onRefresh: () async => ref.invalidate(parcelsProvider),
          child: parcels.isEmpty
              ? ListView(
                  children: const [
                    Padding(
                      padding: EdgeInsets.all(32),
                      child: Text(
                        'No parcels yet. Tap + to add one.',
                        textAlign: TextAlign.center,
                      ),
                    ),
                  ],
                )
              : ListView.builder(
                  padding: const EdgeInsets.all(8),
                  itemCount: parcels.length,
                  itemBuilder: (context, i) {
                    final p = parcels[i];
                    return Card(
                      child: ListTile(
                        title: Text(p.nom),
                        subtitle: Text([
                          if (p.culture != null) p.culture!.nomCommun,
                          if (p.surface != null) '${p.surface} ha',
                        ].join(' · ')),
                        trailing: _StatusChip(p.status),
                        onTap: () => context.push('/parcels/${p.id}'),
                      ),
                    );
                  },
                ),
        ),
      ),
    );
  }
}

class _StatusChip extends StatelessWidget {
  const _StatusChip(this.status);
  final String? status;

  @override
  Widget build(BuildContext context) {
    if (status == null || status!.isEmpty) return const SizedBox.shrink();
    final color = switch (status) {
      'grow' => Colors.green,
      'harvest' => Colors.orange,
      'fallow' => Colors.brown,
      _ => Colors.grey,
    };
    return Chip(
      label: Text(status!),
      labelStyle: TextStyle(color: color.shade800, fontSize: 12),
      backgroundColor: color.shade50,
      side: BorderSide(color: color.shade200),
      visualDensity: VisualDensity.compact,
    );
  }
}
