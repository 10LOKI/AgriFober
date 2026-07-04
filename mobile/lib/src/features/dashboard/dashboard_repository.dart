import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/api_client.dart';
import '../../core/json.dart';
import '../../core/providers.dart';
import '../../models/parcel.dart';

class DashboardData {
  const DashboardData({
    required this.stats,
    required this.recentParcels,
    required this.profileComplete,
  });

  final DashboardStats stats;
  final List<Parcel> recentParcels;
  final bool profileComplete;

  factory DashboardData.fromJson(Map<String, dynamic> json) {
    return DashboardData(
      stats: DashboardStats.fromJson(
          json['statistics'] as Map<String, dynamic>? ?? const {}),
      recentParcels: (json['recent_parcels'] as List?)
              ?.map((e) => Parcel.fromJson(e as Map<String, dynamic>))
              .toList() ??
          const [],
      profileComplete: json['profile_complete'] as bool? ?? false,
    );
  }
}

class DashboardStats {
  const DashboardStats({
    required this.totalParcels,
    required this.activeParcels,
    required this.totalSurfaceHa,
    required this.culturesCultivated,
    this.avgHealthScore,
    required this.aiInteractionsTotal,
  });

  final int totalParcels;
  final int activeParcels;
  final double totalSurfaceHa;
  final int culturesCultivated;
  final double? avgHealthScore;
  final int aiInteractionsTotal;

  factory DashboardStats.fromJson(Map<String, dynamic> json) {
    return DashboardStats(
      totalParcels: asInt(json['total_parcels']) ?? 0,
      activeParcels: asInt(json['active_parcels']) ?? 0,
      totalSurfaceHa: asDouble(json['total_surface_ha']) ?? 0,
      culturesCultivated: asInt(json['cultures_cultivated']) ?? 0,
      avgHealthScore: asDouble(json['avg_health_score']),
      aiInteractionsTotal: asInt(json['ai_interactions_total']) ?? 0,
    );
  }
}

final dashboardRepositoryProvider = Provider<DashboardRepository>((ref) {
  return DashboardRepository(ref.watch(apiClientProvider));
});

class DashboardRepository {
  DashboardRepository(this._api);
  final ApiClient _api;

  Future<DashboardData> fetch() async {
    final data = await _api.get('/farmer/dashboard');
    return DashboardData.fromJson(data as Map<String, dynamic>);
  }
}

/// Auto-fetching async provider the dashboard screen watches.
final dashboardProvider = FutureProvider.autoDispose<DashboardData>((ref) {
  return ref.watch(dashboardRepositoryProvider).fetch();
});
