import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/api_client.dart';
import '../../core/providers.dart';
import '../../models/culture.dart';
import '../../models/product.dart';

final catalogueRepositoryProvider = Provider<CatalogueRepository>((ref) {
  return CatalogueRepository(ref.watch(apiClientProvider));
});

/// Read-only catalogue endpoints: /cultures and /products.
class CatalogueRepository {
  CatalogueRepository(this._api);
  final ApiClient _api;

  Future<List<Culture>> cultures({String? search, String? type}) async {
    final data = await _api.get('/cultures', query: {
      'per_page': 100,
      if (search != null && search.isNotEmpty) 'search': search,
      if (type != null) 'type': type,
    });
    return (data as List)
        .map((e) => Culture.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  /// Show endpoint eager-loads recommended products.
  Future<Culture> culture(int id) async {
    final data = await _api.get('/cultures/$id');
    return Culture.fromJson(data as Map<String, dynamic>);
  }

  Future<List<Product>> products({String? search, String? type}) async {
    final data = await _api.get('/products', query: {
      'per_page': 100,
      if (search != null && search.isNotEmpty) 'search': search,
      if (type != null) 'type': type,
    });
    return (data as List)
        .map((e) => Product.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  Future<Product> product(int id) async {
    final data = await _api.get('/products/$id');
    return Product.fromJson(data as Map<String, dynamic>);
  }
}

// ---- Cultures list state ----

final cultureSearchProvider = StateProvider.autoDispose<String>((_) => '');
final cultureTypeFilterProvider = StateProvider.autoDispose<String?>((_) => null);

final culturesCatalogueProvider =
    FutureProvider.autoDispose<List<Culture>>((ref) {
  final search = ref.watch(cultureSearchProvider);
  final type = ref.watch(cultureTypeFilterProvider);
  return ref
      .watch(catalogueRepositoryProvider)
      .cultures(search: search, type: type);
});

final cultureDetailProvider =
    FutureProvider.autoDispose.family<Culture, int>((ref, id) {
  return ref.watch(catalogueRepositoryProvider).culture(id);
});

// ---- Products list state ----

final productSearchProvider = StateProvider.autoDispose<String>((_) => '');
final productTypeFilterProvider = StateProvider.autoDispose<String?>((_) => null);

final productsCatalogueProvider =
    FutureProvider.autoDispose<List<Product>>((ref) {
  final search = ref.watch(productSearchProvider);
  final type = ref.watch(productTypeFilterProvider);
  return ref
      .watch(catalogueRepositoryProvider)
      .products(search: search, type: type);
});

final productDetailProvider =
    FutureProvider.autoDispose.family<Product, int>((ref, id) {
  return ref.watch(catalogueRepositoryProvider).product(id);
});
