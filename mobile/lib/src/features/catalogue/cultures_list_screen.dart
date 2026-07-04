import 'dart:async';

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import 'catalogue_repository.dart';

const _cultureTypes = {
  'fruit': 'Fruit',
  'legume': 'Légume',
  'cereale': 'Céréale',
  'legumineuse': 'Légumineuse',
  'autre': 'Autre',
};

class CulturesListScreen extends ConsumerStatefulWidget {
  const CulturesListScreen({super.key});

  @override
  ConsumerState<CulturesListScreen> createState() =>
      _CulturesListScreenState();
}

class _CulturesListScreenState extends ConsumerState<CulturesListScreen> {
  Timer? _debounce;

  @override
  void dispose() {
    _debounce?.cancel();
    super.dispose();
  }

  void _onSearchChanged(String value) {
    _debounce?.cancel();
    _debounce = Timer(const Duration(milliseconds: 400), () {
      ref.read(cultureSearchProvider.notifier).state = value;
    });
  }

  @override
  Widget build(BuildContext context) {
    final async = ref.watch(culturesCatalogueProvider);
    final typeFilter = ref.watch(cultureTypeFilterProvider);

    return Scaffold(
      appBar: AppBar(title: const Text('Cultures')),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 12, 16, 4),
            child: TextField(
              onChanged: _onSearchChanged,
              decoration: const InputDecoration(
                prefixIcon: Icon(Icons.search),
                hintText: 'Search cultures…',
                border: OutlineInputBorder(),
                isDense: true,
              ),
            ),
          ),
          SizedBox(
            height: 48,
            child: ListView(
              scrollDirection: Axis.horizontal,
              padding: const EdgeInsets.symmetric(horizontal: 16),
              children: [
                Padding(
                  padding: const EdgeInsets.only(right: 8),
                  child: FilterChip(
                    label: const Text('All'),
                    selected: typeFilter == null,
                    onSelected: (_) => ref
                        .read(cultureTypeFilterProvider.notifier)
                        .state = null,
                  ),
                ),
                ..._cultureTypes.entries.map(
                  (e) => Padding(
                    padding: const EdgeInsets.only(right: 8),
                    child: FilterChip(
                      label: Text(e.value),
                      selected: typeFilter == e.key,
                      onSelected: (_) => ref
                          .read(cultureTypeFilterProvider.notifier)
                          .state = typeFilter == e.key ? null : e.key,
                    ),
                  ),
                ),
              ],
            ),
          ),
          Expanded(
            child: async.when(
              loading: () =>
                  const Center(child: CircularProgressIndicator()),
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
                            ref.invalidate(culturesCatalogueProvider),
                        child: const Text('Retry'),
                      ),
                    ],
                  ),
                ),
              ),
              data: (cultures) => RefreshIndicator(
                onRefresh: () async =>
                    ref.invalidate(culturesCatalogueProvider),
                child: cultures.isEmpty
                    ? ListView(
                        children: const [
                          Padding(
                            padding: EdgeInsets.all(32),
                            child: Text(
                              'No cultures match your search.',
                              textAlign: TextAlign.center,
                            ),
                          ),
                        ],
                      )
                    : ListView.builder(
                        padding: const EdgeInsets.all(8),
                        itemCount: cultures.length,
                        itemBuilder: (context, i) {
                          final c = cultures[i];
                          return Card(
                            child: ListTile(
                              leading: const CircleAvatar(
                                child: Icon(Icons.eco),
                              ),
                              title: Text(c.nomCommun),
                              subtitle: Text([
                                if (c.type != null)
                                  _cultureTypes[c.type] ?? c.type!,
                                if (c.saison != null) c.saison!,
                              ].join(' · ')),
                              trailing:
                                  const Icon(Icons.chevron_right),
                              onTap: () =>
                                  context.push('/cultures/${c.id}'),
                            ),
                          );
                        },
                      ),
              ),
            ),
          ),
        ],
      ),
    );
  }
}
