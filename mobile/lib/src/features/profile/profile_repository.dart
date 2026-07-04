import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/api_client.dart';
import '../../core/json.dart';
import '../../core/providers.dart';
import '../../models/user.dart';

/// GET /farmer/profile payload: user + aggregate farm statistics.
class FarmerProfile {
  const FarmerProfile({
    required this.user,
    required this.totalParcels,
    required this.totalSurfaceHa,
    required this.culturesCultivated,
  });

  final User user;
  final int totalParcels;
  final double totalSurfaceHa;
  final int culturesCultivated;

  factory FarmerProfile.fromJson(Map<String, dynamic> json) {
    final stats = json['statistics'] as Map<String, dynamic>? ?? const {};
    return FarmerProfile(
      user: User.fromJson(json['profile'] as Map<String, dynamic>),
      totalParcels: asInt(stats['total_parcels']) ?? 0,
      totalSurfaceHa: asDouble(stats['total_surface_ha']) ?? 0,
      culturesCultivated:
          asInt(stats['cultures_cultivated']) ?? 0,
    );
  }
}

final profileRepositoryProvider = Provider<ProfileRepository>((ref) {
  return ProfileRepository(ref.watch(apiClientProvider));
});

class ProfileRepository {
  ProfileRepository(this._api);
  final ApiClient _api;

  Future<FarmerProfile> fetch() async {
    final data = await _api.get('/farmer/profile');
    return FarmerProfile.fromJson(data as Map<String, dynamic>);
  }

  Future<User> update(Map<String, dynamic> payload) async {
    final data = await _api.put('/farmer/profile', body: payload);
    return User.fromJson(data as Map<String, dynamic>);
  }
}

final profileProvider = FutureProvider.autoDispose<FarmerProfile>((ref) {
  return ref.watch(profileRepositoryProvider).fetch();
});
