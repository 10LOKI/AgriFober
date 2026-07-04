import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/api_client.dart';
import '../../core/providers.dart';
import '../../models/culture.dart';
import '../../models/parcel.dart';
import '../../models/recommendation.dart';

final parcelRepositoryProvider = Provider<ParcelRepository>((ref) {
  return ParcelRepository(ref.watch(apiClientProvider));
});

/// Talks to /parcels (apiResource) and /cultures in routes/api.php.
class ParcelRepository {
  ParcelRepository(this._api);
  final ApiClient _api;

  Future<List<Parcel>> list() async {
    final data = await _api.get('/parcels');
    return (data as List)
        .map((e) => Parcel.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  Future<Parcel> show(int id) async {
    final data = await _api.get('/parcels/$id');
    return Parcel.fromJson(data as Map<String, dynamic>);
  }

  Future<Parcel> create(Map<String, dynamic> payload) async {
    final data = await _api.post('/parcels', body: payload);
    return Parcel.fromJson(data as Map<String, dynamic>);
  }

  Future<Parcel> update(int id, Map<String, dynamic> payload) async {
    final data = await _api.put('/parcels/$id', body: payload);
    return Parcel.fromJson(data as Map<String, dynamic>);
  }

  Future<void> delete(int id) => _api.delete('/parcels/$id');

  /// Products recommended for the parcel's culture.
  Future<List<Recommendation>> recommendations(int id) async {
    final data = await _api.get('/parcels/$id/recommendations');
    return (data as List)
        .map((e) => Recommendation.fromJson(e as Map<String, dynamic>))
        .toList();
  }

  /// Catalogue for the culture dropdown in the parcel form.
  Future<List<Culture>> cultures() async {
    final data = await _api.get('/cultures', query: {'per_page': 100});
    return (data as List)
        .map((e) => Culture.fromJson(e as Map<String, dynamic>))
        .toList();
  }
}

final parcelsProvider = FutureProvider.autoDispose<List<Parcel>>((ref) {
  return ref.watch(parcelRepositoryProvider).list();
});

final parcelProvider =
    FutureProvider.autoDispose.family<Parcel, int>((ref, id) {
  return ref.watch(parcelRepositoryProvider).show(id);
});

final culturesProvider = FutureProvider.autoDispose<List<Culture>>((ref) {
  return ref.watch(parcelRepositoryProvider).cultures();
});

final recommendationsProvider = FutureProvider.autoDispose
    .family<List<Recommendation>, int>((ref, id) {
  return ref.watch(parcelRepositoryProvider).recommendations(id);
});
