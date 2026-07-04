import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../core/api_exception.dart';
import '../../models/recommendation.dart';
import '../../models/weather.dart';
import '../../widgets/async_view.dart';
import '../../widgets/skeleton.dart';
import '../../widgets/status_chip.dart';
import 'parcel_repository.dart';
import 'weather_repository.dart';

class ParcelDetailScreen extends ConsumerWidget {
  const ParcelDetailScreen({super.key, required this.parcelId});
  final int parcelId;

  Future<void> _confirmDelete(BuildContext context, WidgetRef ref) async {
    final confirmed = await showDialog<bool>(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Delete parcel?'),
        content: const Text('This cannot be undone.'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(ctx, false),
            child: const Text('Cancel'),
          ),
          FilledButton(
            onPressed: () => Navigator.pop(ctx, true),
            style: FilledButton.styleFrom(
              backgroundColor: Theme.of(ctx).colorScheme.error,
            ),
            child: const Text('Delete'),
          ),
        ],
      ),
    );
    if (confirmed != true || !context.mounted) return;

    try {
      await ref.read(parcelRepositoryProvider).delete(parcelId);
      ref.invalidate(parcelsProvider);
      if (context.mounted) context.go('/parcels');
    } on ApiException catch (e) {
      if (context.mounted) {
        ScaffoldMessenger.of(context)
            .showSnackBar(SnackBar(content: Text(e.message)));
      }
    }
  }

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final async = ref.watch(parcelProvider(parcelId));

    return Scaffold(
      appBar: AppBar(
        title: Text(async.valueOrNull?.nom ?? 'Parcel'),
        actions: [
          IconButton(
            icon: const Icon(Icons.edit_outlined),
            onPressed: () => context.go('/parcels/$parcelId/edit'),
          ),
          IconButton(
            icon: const Icon(Icons.delete_outline),
            onPressed: () => _confirmDelete(context, ref),
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () => context.go('/assistant?parcel=$parcelId'),
        icon: const Icon(Icons.smart_toy_outlined),
        label: const Text('Ask AI'),
      ),
      body: async.when(
        loading: () => const SkeletonList(count: 5, cardHeight: 100),
        error: (e, _) => ErrorView(
          message: '$e',
          onRetry: () => ref.invalidate(parcelProvider(parcelId)),
        ),
        data: (p) => RefreshIndicator(
          onRefresh: () async {
            ref.invalidate(parcelProvider(parcelId));
            ref.invalidate(currentWeatherProvider(parcelId));
            ref.invalidate(forecastProvider(parcelId));
            ref.invalidate(recommendationsProvider(parcelId));
          },
          child: ListView(
            padding: const EdgeInsets.fromLTRB(16, 8, 16, 88),
            children: [
              // -- Overview card --
              Card(
                child: Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    children: [
                      Row(
                        children: [
                          HealthBadge(score: p.healthScore, size: 56),
                          if (p.healthScore != null)
                            const SizedBox(width: 16),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(
                                  p.culture?.nomCommun ?? 'No culture',
                                  style: Theme.of(context)
                                      .textTheme
                                      .titleMedium
                                      ?.copyWith(
                                          fontWeight: FontWeight.w700),
                                ),
                                const SizedBox(height: 2),
                                Text(
                                  p.surface != null
                                      ? '${p.surface} ha'
                                      : 'Surface not set',
                                  style: Theme.of(context)
                                      .textTheme
                                      .bodySmall
                                      ?.copyWith(
                                          color:
                                              Theme.of(context).hintColor),
                                ),
                              ],
                            ),
                          ),
                          StatusChip(p.status),
                        ],
                      ),
                      const SizedBox(height: 16),
                      Row(
                        children: [
                          Expanded(
                            child: _DateInfo(
                              icon: Icons.calendar_today_outlined,
                              label: 'Planted',
                              value: _fmtDate(p.datePlantation),
                            ),
                          ),
                          Expanded(
                            child: _DateInfo(
                              icon: Icons.agriculture_outlined,
                              label: 'Est. harvest',
                              value: _fmtDate(p.dateRecolteEstimee),
                            ),
                          ),
                          if (p.latitude != null && p.longitude != null)
                            Expanded(
                              child: _DateInfo(
                                icon: Icons.place_outlined,
                                label: 'Location',
                                value:
                                    '${p.latitude!.toStringAsFixed(2)}, ${p.longitude!.toStringAsFixed(2)}',
                              ),
                            ),
                        ],
                      ),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 8),

              const SectionHeader('Weather'),
              _WeatherSection(parcelId: parcelId),
              const SizedBox(height: 8),

              const SectionHeader('Recommended products'),
              _RecommendationsSection(parcelId: parcelId),

              if (p.culture?.conseils != null &&
                  p.culture!.conseils!.isNotEmpty) ...[
                const SizedBox(height: 8),
                const SectionHeader('Culture advice'),
                Card(
                  child: Padding(
                    padding: const EdgeInsets.all(16),
                    child: Row(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Icon(
                          Icons.tips_and_updates_outlined,
                          size: 20,
                          color: Theme.of(context).colorScheme.secondary,
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: Text(
                            p.culture!.conseils!,
                            style: const TextStyle(height: 1.5),
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              ],
            ],
          ),
        ),
      ),
    );
  }

  static String _fmtDate(DateTime? d) => d == null
      ? '—'
      : '${d.year}-${d.month.toString().padLeft(2, '0')}-${d.day.toString().padLeft(2, '0')}';
}

class _DateInfo extends StatelessWidget {
  const _DateInfo({
    required this.icon,
    required this.label,
    required this.value,
  });

  final IconData icon;
  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Icon(icon, size: 18, color: Theme.of(context).hintColor),
        const SizedBox(height: 4),
        Text(
          value,
          style: const TextStyle(fontSize: 12.5, fontWeight: FontWeight.w600),
          textAlign: TextAlign.center,
        ),
        Text(
          label,
          style: Theme.of(context)
              .textTheme
              .bodySmall
              ?.copyWith(color: Theme.of(context).hintColor, fontSize: 11),
        ),
      ],
    );
  }
}

/// Products recommended for this parcel's culture, as expandable cards.
class _RecommendationsSection extends ConsumerWidget {
  const _RecommendationsSection({required this.parcelId});
  final int parcelId;

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final async = ref.watch(recommendationsProvider(parcelId));

    return async.when(
      loading: () => const SkeletonCard(height: 72),
      error: (_, __) => Card(
        child: ListTile(
          leading: const Icon(Icons.error_outline),
          title: const Text('Recommendations unavailable'),
          trailing: IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: () =>
                ref.invalidate(recommendationsProvider(parcelId)),
          ),
        ),
      ),
      data: (recs) => recs.isEmpty
          ? Card(
              child: ListTile(
                leading: Icon(
                  Icons.inventory_2_outlined,
                  color: Theme.of(context).hintColor,
                ),
                title: const Text('No product recommendations'),
                subtitle: const Text(
                    'Assign a culture to this parcel to get suggestions.'),
              ),
            )
          : Column(
              children: recs
                  .map((r) => _RecommendationCard(recommendation: r))
                  .toList(),
            ),
    );
  }
}

class _RecommendationCard extends StatelessWidget {
  const _RecommendationCard({required this.recommendation});
  final Recommendation recommendation;

  static const _typeLabels = {
    'engrais': ('Fertilizer', Icons.grass, Colors.green),
    'pesticide': ('Pesticide', Icons.pest_control, Colors.red),
    'herbicide': ('Herbicide', Icons.local_florist, Colors.orange),
    'fongicide': ('Fungicide', Icons.coronavirus_outlined, Colors.purple),
  };

  @override
  Widget build(BuildContext context) {
    final r = recommendation;
    final meta = _typeLabels[r.type];
    final color = meta?.$3 ?? Colors.blueGrey;

    return Card(
      clipBehavior: Clip.antiAlias,
      child: ExpansionTile(
        leading: CircleAvatar(
          backgroundColor: color.withValues(alpha: 0.12),
          child: Icon(meta?.$2 ?? Icons.science_outlined,
              size: 20, color: color),
        ),
        title: Text(
          r.nomCommercial,
          style: const TextStyle(fontWeight: FontWeight.w600),
        ),
        subtitle: Text(meta?.$1 ?? (r.type ?? 'Product')),
        childrenPadding: const EdgeInsets.fromLTRB(16, 0, 16, 16),
        expandedCrossAxisAlignment: CrossAxisAlignment.start,
        children: [
          if (r.description != null && r.description!.isNotEmpty) ...[
            Text(r.description!, style: const TextStyle(height: 1.4)),
            const SizedBox(height: 10),
          ],
          if (r.composantActif != null)
            _RecRow('Active component', r.composantActif!),
          if (r.dosageSpecifique != null || r.dosageRecommande != null)
            _RecRow('Dosage', r.dosageSpecifique ?? r.dosageRecommande!),
          if (r.delaiAvantRecolte != null)
            _RecRow('Pre-harvest delay', '${r.delaiAvantRecolte} days'),
          if (r.notes != null && r.notes!.isNotEmpty)
            _RecRow('Notes', r.notes!),
          if (r.safetyInstructions != null &&
              r.safetyInstructions!.isNotEmpty) ...[
            const SizedBox(height: 8),
            Container(
              padding: const EdgeInsets.all(10),
              decoration: BoxDecoration(
                color: Theme.of(context)
                    .colorScheme
                    .errorContainer
                    .withValues(alpha: 0.4),
                borderRadius: BorderRadius.circular(10),
              ),
              child: Row(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Icon(Icons.warning_amber_rounded,
                      size: 18, color: Theme.of(context).colorScheme.error),
                  const SizedBox(width: 8),
                  Expanded(
                    child: Text(
                      r.safetyInstructions!,
                      style: const TextStyle(fontSize: 12.5, height: 1.4),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ],
      ),
    );
  }
}

class _RecRow extends StatelessWidget {
  const _RecRow(this.label, this.value);
  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 6),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 130,
            child: Text(
              label,
              style: TextStyle(
                fontSize: 12.5,
                color: Theme.of(context).hintColor,
              ),
            ),
          ),
          Expanded(
            child: Text(
              value,
              style: const TextStyle(
                fontSize: 12.5,
                fontWeight: FontWeight.w600,
              ),
            ),
          ),
        ],
      ),
    );
  }
}

/// Current conditions card + 5-day forecast strip.
/// Loads independently so a weather failure never blocks parcel details.
class _WeatherSection extends ConsumerWidget {
  const _WeatherSection({required this.parcelId});
  final int parcelId;

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final current = ref.watch(currentWeatherProvider(parcelId));
    final forecast = ref.watch(forecastProvider(parcelId));

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        current.when(
          loading: () => const SkeletonCard(height: 100),
          error: (e, _) => Card(
            child: ListTile(
              leading: const Icon(Icons.cloud_off),
              title: const Text('Weather unavailable'),
              subtitle: Text('$e'),
              trailing: IconButton(
                icon: const Icon(Icons.refresh),
                onPressed: () =>
                    ref.invalidate(currentWeatherProvider(parcelId)),
              ),
            ),
          ),
          data: (w) => _CurrentWeatherCard(weather: w),
        ),
        const SizedBox(height: 8),
        forecast.when(
          loading: () => const SizedBox.shrink(),
          error: (_, __) => const SizedBox.shrink(),
          data: (days) => days.isEmpty
              ? const SizedBox.shrink()
              : SizedBox(
                  height: 130,
                  child: ListView.separated(
                    scrollDirection: Axis.horizontal,
                    itemCount: days.length,
                    separatorBuilder: (_, __) => const SizedBox(width: 8),
                    itemBuilder: (context, i) =>
                        _ForecastCard(day: days[i]),
                  ),
                ),
        ),
      ],
    );
  }
}

IconData _conditionIcon(String? condition) {
  final c = (condition ?? '').toLowerCase();
  if (c.contains('soleil') || c.contains('ensoleill')) return Icons.wb_sunny;
  if (c.contains('pluie') || c.contains('averse')) return Icons.water_drop;
  if (c.contains('orage')) return Icons.thunderstorm;
  if (c.contains('nuag') || c.contains('couvert')) return Icons.cloud;
  return Icons.wb_cloudy;
}

class _CurrentWeatherCard extends StatelessWidget {
  const _CurrentWeatherCard({required this.weather});
  final CurrentWeather weather;

  @override
  Widget build(BuildContext context) {
    final w = weather;
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Icon(
                  _conditionIcon(w.condition),
                  size: 40,
                  color: Theme.of(context).colorScheme.primary,
                ),
                const SizedBox(width: 16),
                Text(
                  w.temp != null ? '${w.temp!.toStringAsFixed(0)}°C' : '—',
                  style: Theme.of(context).textTheme.headlineMedium,
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Text(w.condition ?? '',
                      style: Theme.of(context).textTheme.bodyLarge),
                ),
                if (w.isSimulated)
                  Chip(
                    label: const Text('simulated'),
                    labelStyle: const TextStyle(fontSize: 11),
                    visualDensity: VisualDensity.compact,
                    backgroundColor:
                        Theme.of(context).colorScheme.surfaceContainerHighest,
                  ),
              ],
            ),
            const SizedBox(height: 12),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceAround,
              children: [
                _WeatherStat(
                  icon: Icons.water_drop_outlined,
                  label: 'Humidity',
                  value: w.humidity != null
                      ? '${w.humidity!.toStringAsFixed(0)}%'
                      : '—',
                ),
                _WeatherStat(
                  icon: Icons.air,
                  label: 'Wind',
                  value: w.windSpeed != null
                      ? '${w.windSpeed!.toStringAsFixed(0)} km/h'
                      : '—',
                ),
                _WeatherStat(
                  icon: Icons.umbrella_outlined,
                  label: 'Rain',
                  value: w.precipitation != null
                      ? '${w.precipitation!.toStringAsFixed(1)} mm'
                      : '—',
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}

class _WeatherStat extends StatelessWidget {
  const _WeatherStat({
    required this.icon,
    required this.label,
    required this.value,
  });
  final IconData icon;
  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Icon(icon, size: 20, color: Theme.of(context).hintColor),
        const SizedBox(height: 4),
        Text(value, style: const TextStyle(fontWeight: FontWeight.w600)),
        Text(label, style: Theme.of(context).textTheme.bodySmall),
      ],
    );
  }
}

class _ForecastCard extends StatelessWidget {
  const _ForecastCard({required this.day});
  final ForecastDay day;

  @override
  Widget build(BuildContext context) {
    // date is Y-m-d; show just month-day.
    final label = day.date.length >= 10 ? day.date.substring(5) : day.date;
    return Card(
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Text(label, style: Theme.of(context).textTheme.bodySmall),
            const SizedBox(height: 6),
            Icon(
              _conditionIcon(day.condition),
              size: 24,
              color: Theme.of(context).colorScheme.primary,
            ),
            const SizedBox(height: 6),
            Text(
              '${day.tempMax?.toStringAsFixed(0) ?? '—'}° / '
              '${day.tempMin?.toStringAsFixed(0) ?? '—'}°',
              style: const TextStyle(fontWeight: FontWeight.w600),
            ),
            if (day.precipitationChance != null) ...[
              const SizedBox(height: 4),
              Text(
                '${day.precipitationChance}% rain',
                style: Theme.of(context).textTheme.bodySmall,
              ),
            ],
          ],
        ),
      ),
    );
  }
}
