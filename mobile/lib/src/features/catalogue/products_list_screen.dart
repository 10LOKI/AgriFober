import 'dart:async';

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import 'catalogue_repository.dart';

const _productTypes = {
  'engrais': 'Engrais',
  'pesticide': 'Pesticide',
  'fongicide': 'Fongicide',
  'herbicide': 'Herbicide',
  'biologique': 'Biologique',
};

class ProductsListScreen extends ConsumerStatefulWidget {
  const ProductsListScreen({super.key});

  @override
  ConsumerState<ProductsListScreen> createState() =>
      _ProductsListScreenState();
}

class _ProductsListScreenState extends ConsumerState<ProductsListScreen> {
  Timer? _debounce;

  @override
  void dispose() {
    _debounce?.cancel();
    super.dispose();
  }

  void _onSearchChanged(String value) {
    _debounce?.cancel();
    _debounce = Timer(const Duration(milliseconds: 400), () {
      ref.read(productSearchProvider.notifier).state = value;
    });
  }

  @override
  Widget build(BuildContext context) {
    final async = ref.watch(productsCatalogueProvider);
    final typeFilter = ref.watch(productTypeFilterProvider);

    return Scaffold(
      appBar: AppBar(title: const Text('Products')),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 12, 16, 4),
            child: TextField(
              onChanged: _onSearchChanged,
              decoration: const InputDecoration(
                prefixIcon: Icon(Icons.search),
                hintText: 'Search products…',
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
                        .read(productTypeFilterProvider.notifier)
                        .state = null,
                  ),
                ),
                ..._productTypes.entries.map(
                  (e) => Padding(
                    padding: const EdgeInsets.only(right: 8),
                    child: FilterChip(
                      label: Text(e.value),
                      selected: typeFilter == e.key,
                      onSelected: (_) => ref
                          .read(productTypeFilterProvider.notifier)
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
                            ref.invalidate(productsCatalogueProvider),
                        child: const Text('Retry'),
                      ),
                    ],
                  ),
                ),
              ),
              data: (products) => RefreshIndicator(
                onRefresh: () async =>
                    ref.invalidate(productsCatalogueProvider),
                child: products.isEmpty
                    ? ListView(
                        children: const [
                          Padding(
                            padding: EdgeInsets.all(32),
                            child: Text(
                              'No products match your search.',
                              textAlign: TextAlign.center,
                            ),
                          ),
                        ],
                      )
                    : ListView.builder(
                        padding: const EdgeInsets.all(8),
                        itemCount: products.length,
                        itemBuilder: (context, i) {
                          final p = products[i];
                          return Card(
                            child: ListTile(
                              leading: const CircleAvatar(
                                child:
                                    Icon(Icons.inventory_2_outlined),
                              ),
                              title: Text(p.nomCommercial),
                              subtitle: Text([
                                if (p.type != null)
                                  _productTypes[p.type] ?? p.type!,
                                if (p.composantActif != null)
                                  p.composantActif!,
                              ].join(' · ')),
                              trailing:
                                  const Icon(Icons.chevron_right),
                              onTap: () =>
                                  context.push('/products/${p.id}'),
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
