import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../widgets/async_view.dart';
import '../../widgets/skeleton.dart';
import '../../widgets/status_chip.dart';
import '../auth/auth_controller.dart';
import 'dashboard_repository.dart';

class DashboardScreen extends ConsumerWidget {
  const DashboardScreen({super.key});

  static String _greeting() {
    final h = DateTime.now().hour;
    if (h < 12) return 'Good morning';
    if (h < 18) return 'Good afternoon';
    return 'Good evening';
  }

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final async = ref.watch(dashboardProvider);
    final user = ref.watch(authControllerProvider).user;
    final scheme = Theme.of(context).colorScheme;

    return Scaffold(
      appBar: AppBar(
        title: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              _greeting(),
              style: Theme.of(context)
                  .textTheme
                  .bodySmall
                  ?.copyWith(color: Theme.of(context).hintColor),
            ),
            Text(user?.name ?? ''),
          ],
        ),
        toolbarHeight: 68,
      ),
      body: async.when(
        loading: () => const _DashboardSkeleton(),
        error: (e, _) => ErrorView(
          message: '$e',
          onRetry: () => ref.invalidate(dashboardProvider),
        ),
        data: (d) => RefreshIndicator(
          onRefresh: () async => ref.invalidate(dashboardProvider),
          child: ListView(
            padding: const EdgeInsets.fromLTRB(16, 8, 16, 24),
            children: [
              if (!d.profileComplete) ...[
                _ProfileBanner(onTap: () => context.go('/profile/edit')),
                const SizedBox(height: 12),
              ],

              // -- Farm overview hero --
              _HeroCard(stats: d.stats),
              const SizedBox(height: 12),

              // -- Secondary stats --
              Row(
                children: [
                  Expanded(
                    child: _StatCard(
                      icon: Icons.eco,
                      label: 'Cultures',
                      value: '${d.stats.culturesCultivated}',
                    ),
                  ),
                  const SizedBox(width: 10),
                  Expanded(
                    child: _StatCard(
                      icon: Icons.smart_toy_outlined,
                      label: 'AI chats',
                      value: '${d.stats.aiInteractionsTotal}',
                    ),
                  ),
                  const SizedBox(width: 10),
                  Expanded(
                    child: _StatCard(
                      icon: Icons.trending_up,
                      label: 'Active',
                      value: '${d.stats.activeParcels}',
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 20),

              // -- Quick actions --
              const SectionHeader('Quick actions'),
              Row(
                children: [
                  Expanded(
                    child: _ActionCard(
                      icon: Icons.add_location_alt_outlined,
                      label: 'Add parcel',
                      color: scheme.primary,
                      onTap: () => context.go('/parcels/new'),
                    ),
                  ),
                  const SizedBox(width: 10),
                  Expanded(
                    child: _ActionCard(
                      icon: Icons.smart_toy_outlined,
                      label: 'Ask AI',
                      color: scheme.secondary,
                      onTap: () => context.go('/assistant'),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 8),
              Row(
                children: [
                  Expanded(
                    child: _ActionCard(
                      icon: Icons.eco_outlined,
                      label: 'Cultures',
                      color: Colors.teal,
                      onTap: () => context.go('/cultures'),
                    ),
                  ),
                  const SizedBox(width: 10),
                  Expanded(
                    child: _ActionCard(
                      icon: Icons.inventory_2_outlined,
                      label: 'Products',
                      color: Colors.indigo,
                      onTap: () => context.go('/products'),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 20),

              // -- Recent parcels --
              SectionHeader(
                'Recent parcels',
                actionLabel: 'See all',
                onAction: () => context.go('/parcels'),
              ),
              if (d.recentParcels.isEmpty)
                EmptyView(
                  icon: Icons.grid_view,
                  title: 'No parcels yet',
                  subtitle: 'Create your first parcel to start tracking.',
                  actionLabel: 'Add parcel',
                  onAction: () => context.go('/parcels/new'),
                )
              else
                ...d.recentParcels.map(
                  (p) => Card(
                    child: ListTile(
                      leading: HealthBadge(score: p.healthScore, size: 40),
                      title: Text(
                        p.nom,
                        style: const TextStyle(fontWeight: FontWeight.w600),
                      ),
                      subtitle: Text(p.culture?.nomCommun ?? 'No culture'),
                      trailing: StatusChip(p.status),
                      onTap: () => context.go('/parcels/${p.id}'),
                    ),
                  ),
                ),
            ],
          ),
        ),
      ),
    );
  }
}

/// Gradient farm-overview card: surface, parcel count, average health.
class _HeroCard extends StatelessWidget {
  const _HeroCard({required this.stats});
  final DashboardStats stats;

  @override
  Widget build(BuildContext context) {
    final scheme = Theme.of(context).colorScheme;
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        gradient: LinearGradient(
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
          colors: [scheme.primary, scheme.primary.withValues(alpha: 0.75)],
        ),
        borderRadius: BorderRadius.circular(20),
      ),
      child: Row(
        children: [
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  'My farm',
                  style: TextStyle(
                    fontSize: 13,
                    fontWeight: FontWeight.w600,
                    color: scheme.onPrimary.withValues(alpha: 0.8),
                  ),
                ),
                const SizedBox(height: 8),
                Text(
                  '${stats.totalSurfaceHa} ha',
                  style: TextStyle(
                    fontSize: 32,
                    fontWeight: FontWeight.w800,
                    color: scheme.onPrimary,
                    letterSpacing: -1,
                  ),
                ),
                Text(
                  '${stats.totalParcels} parcel${stats.totalParcels == 1 ? '' : 's'} · '
                  '${stats.activeParcels} growing',
                  style: TextStyle(
                    fontSize: 13,
                    color: scheme.onPrimary.withValues(alpha: 0.85),
                  ),
                ),
              ],
            ),
          ),
          if (stats.avgHealthScore != null)
            Column(
              children: [
                SizedBox(
                  width: 64,
                  height: 64,
                  child: Stack(
                    alignment: Alignment.center,
                    children: [
                      CircularProgressIndicator(
                        value: stats.avgHealthScore!.clamp(0, 100) / 100,
                        strokeWidth: 6,
                        backgroundColor:
                            scheme.onPrimary.withValues(alpha: 0.25),
                        valueColor:
                            AlwaysStoppedAnimation(scheme.onPrimary),
                      ),
                      Text(
                        stats.avgHealthScore!.toStringAsFixed(0),
                        style: TextStyle(
                          fontSize: 18,
                          fontWeight: FontWeight.w800,
                          color: scheme.onPrimary,
                        ),
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 6),
                Text(
                  'Avg. health',
                  style: TextStyle(
                    fontSize: 11,
                    color: scheme.onPrimary.withValues(alpha: 0.85),
                  ),
                ),
              ],
            ),
        ],
      ),
    );
  }
}

class _ProfileBanner extends StatelessWidget {
  const _ProfileBanner({required this.onTap});
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    final scheme = Theme.of(context).colorScheme;
    return Material(
      color: scheme.secondaryContainer.withValues(alpha: 0.6),
      borderRadius: BorderRadius.circular(14),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(14),
        child: Padding(
          padding: const EdgeInsets.all(14),
          child: Row(
            children: [
              Icon(Icons.person_pin_circle_outlined,
                  color: scheme.onSecondaryContainer),
              const SizedBox(width: 12),
              Expanded(
                child: Text(
                  'Complete your profile (region & experience) to get '
                  'better recommendations.',
                  style: TextStyle(
                    fontSize: 13,
                    color: scheme.onSecondaryContainer,
                  ),
                ),
              ),
              const Icon(Icons.chevron_right, size: 20),
            ],
          ),
        ),
      ),
    );
  }
}

class _StatCard extends StatelessWidget {
  const _StatCard({
    required this.icon,
    required this.label,
    required this.value,
  });

  final IconData icon;
  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    final scheme = Theme.of(context).colorScheme;
    return Card(
      child: Padding(
        padding: const EdgeInsets.symmetric(vertical: 14),
        child: Column(
          children: [
            Icon(icon, size: 20, color: scheme.primary),
            const SizedBox(height: 6),
            Text(
              value,
              style: Theme.of(context)
                  .textTheme
                  .titleMedium
                  ?.copyWith(fontWeight: FontWeight.w800),
            ),
            Text(
              label,
              style: Theme.of(context)
                  .textTheme
                  .bodySmall
                  ?.copyWith(color: Theme.of(context).hintColor),
            ),
          ],
        ),
      ),
    );
  }
}

class _ActionCard extends StatelessWidget {
  const _ActionCard({
    required this.icon,
    required this.label,
    required this.color,
    required this.onTap,
  });

  final IconData icon;
  final String label;
  final Color color;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return Card(
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(16),
        child: Padding(
          padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 14),
          child: Row(
            children: [
              Container(
                padding: const EdgeInsets.all(8),
                decoration: BoxDecoration(
                  color: color.withValues(alpha: 0.12),
                  borderRadius: BorderRadius.circular(10),
                ),
                child: Icon(icon, size: 20, color: color),
              ),
              const SizedBox(width: 10),
              Expanded(
                child: Text(
                  label,
                  style: const TextStyle(
                    fontSize: 13.5,
                    fontWeight: FontWeight.w600,
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _DashboardSkeleton extends StatelessWidget {
  const _DashboardSkeleton();

  @override
  Widget build(BuildContext context) {
    return ListView(
      padding: const EdgeInsets.all(16),
      physics: const NeverScrollableScrollPhysics(),
      children: const [
        Skeleton(height: 120, radius: 20),
        SizedBox(height: 12),
        Row(
          children: [
            Expanded(child: Skeleton(height: 84, radius: 16)),
            SizedBox(width: 10),
            Expanded(child: Skeleton(height: 84, radius: 16)),
            SizedBox(width: 10),
            Expanded(child: Skeleton(height: 84, radius: 16)),
          ],
        ),
        SizedBox(height: 20),
        Skeleton(width: 140, height: 18),
        SizedBox(height: 12),
        SkeletonCard(),
        SkeletonCard(),
        SkeletonCard(),
      ],
    );
  }
}
