import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../models/parcel.dart';
import '../../widgets/async_view.dart';
import '../../widgets/skeleton.dart';
import '../../widgets/status_chip.dart';
import 'parcel_repository.dart';

/// Client-side filters over the loaded parcels list.
final parcelSearchProvider = StateProvider.autoDispose<String>((_) => '');
final parcelStatusFilterProvider =
    StateProvider.autoDispose<String?>((_) => null);

final filteredParcelsProvider =
    Provider.autoDispose<AsyncValue<List<Parcel>>>((ref) {
  final async = ref.watch(parcelsProvider);
  final search = ref.watch(parcelSearchProvider).toLowerCase();
  final status = ref.watch(parcelStatusFilterProvider);

  return async.whenData((parcels) => parcels.where((p) {
        final matchesSearch = search.isEmpty ||
            p.nom.toLowerCase().contains(search) ||
            (p.culture?.nomCommun.toLowerCase().contains(search) ?? false);
        final matchesStatus = status == null || p.status == status;
        return matchesSearch && matchesStatus;
      }).toList());
});

class ParcelsListScreen extends ConsumerWidget {
  const ParcelsListScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final async = ref.watch(filteredParcelsProvider);
    final statusFilter = ref.watch(parcelStatusFilterProvider);
    final hasFilters = ref.watch(parcelSearchProvider).isNotEmpty ||
        statusFilter != null;

    return Scaffold(
      appBar: AppBar(title: const Text('My parcels')),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () => context.go('/parcels/new'),
        icon: const Icon(Icons.add),
        label: const Text('Parcel'),
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 8, 16, 4),
            child: TextField(
              onChanged: (v) =>
                  ref.read(parcelSearchProvider.notifier).state = v,
              decoration: const InputDecoration(
                prefixIcon: Icon(Icons.search),
                hintText: 'Search by name or culture…',
                isDense: true,
              ),
            ),
          ),
          SizedBox(
            height: 52,
            child: ListView(
              scrollDirection: Axis.horizontal,
              padding:
                  const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
              children: [
                Padding(
                  padding: const EdgeInsets.only(right: 8),
                  child: FilterChip(
                    label: const Text('All'),
                    selected: statusFilter == null,
                    onSelected: (_) => ref
                        .read(parcelStatusFilterProvider.notifier)
                        .state = null,
                  ),
                ),
                ...parcelStatuses.entries.map(
                  (e) => Padding(
                    padding: const EdgeInsets.only(right: 8),
                    child: FilterChip(
                      avatar: Icon(e.value.icon, size: 16),
                      label: Text(e.value.label),
                      selected: statusFilter == e.key,
                      onSelected: (_) => ref
                          .read(parcelStatusFilterProvider.notifier)
                          .state = statusFilter == e.key ? null : e.key,
                    ),
                  ),
                ),
              ],
            ),
          ),
          Expanded(
            child: async.when(
              loading: () => const SkeletonList(count: 6, cardHeight: 88),
              error: (e, _) => ErrorView(
                message: '$e',
                onRetry: () => ref.invalidate(parcelsProvider),
              ),
              data: (parcels) => RefreshIndicator(
                onRefresh: () async => ref.invalidate(parcelsProvider),
                child: parcels.isEmpty
                    ? ListView(
                        children: [
                          const SizedBox(height: 60),
                          hasFilters
                              ? const EmptyView(
                                  icon: Icons.search_off,
                                  title: 'No matching parcels',
                                  subtitle:
                                      'Try a different search or filter.',
                                )
                              : EmptyView(
                                  icon: Icons.grid_view,
                                  title: 'No parcels yet',
                                  subtitle:
                                      'Create your first parcel to start '
                                      'tracking your crops.',
                                  actionLabel: 'Add parcel',
                                  onAction: () =>
                                      context.go('/parcels/new'),
                                ),
                        ],
                      )
                    : ListView.builder(
                        padding: const EdgeInsets.fromLTRB(16, 4, 16, 88),
                        itemCount: parcels.length,
                        itemBuilder: (context, i) =>
                            _ParcelCard(parcel: parcels[i]),
                      ),
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class _ParcelCard extends StatelessWidget {
  const _ParcelCard({required this.parcel});
  final Parcel parcel;

  @override
  Widget build(BuildContext context) {
    final p = parcel;
    return Card(
      child: InkWell(
        onTap: () => context.go('/parcels/${p.id}'),
        borderRadius: BorderRadius.circular(16),
        child: Padding(
          padding: const EdgeInsets.all(14),
          child: Row(
            children: [
              HealthBadge(score: p.healthScore, size: 46),
              if (p.healthScore != null) const SizedBox(width: 14),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      p.nom,
                      style: const TextStyle(
                        fontSize: 15,
                        fontWeight: FontWeight.w700,
                      ),
                    ),
                    const SizedBox(height: 3),
                    Text(
                      [
                        p.culture?.nomCommun ?? 'No culture',
                        if (p.surface != null) '${p.surface} ha',
                      ].join(' · '),
                      style: Theme.of(context)
                          .textTheme
                          .bodySmall
                          ?.copyWith(color: Theme.of(context).hintColor),
                    ),
                  ],
                ),
              ),
              StatusChip(p.status),
            ],
          ),
        ),
      ),
    );
  }
}
