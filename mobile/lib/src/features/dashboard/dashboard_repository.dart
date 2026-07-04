import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/api_client.dart';
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
      totalParcels: (json['total_parcels'] as num?)?.toInt() ?? 0,
      activeParcels: (json['active_parcels'] as num?)?.toInt() ?? 0,
      totalSurfaceHa: (json['total_surface_ha'] as num?)?.toDouble() ?? 0,
      culturesCultivated: (json['cultures_cultivated'] as num?)?.toInt() ?? 0,
      avgHealthScore: (json['avg_health_score'] as num?)?.toDouble(),
      aiInteractionsTotal: (json['ai_interactions_total'] as num?)?.toInt() ?? 0,
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
