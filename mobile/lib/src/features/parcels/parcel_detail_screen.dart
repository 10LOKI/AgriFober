import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../core/api_exception.dart';
import '../../models/weather.dart';
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
            style: FilledButton.styleFrom(backgroundColor: Colors.red),
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
            icon: const Icon(Icons.edit),
            onPressed: () => context.push('/parcels/$parcelId/edit'),
          ),
          IconButton(
            icon: const Icon(Icons.delete_outline),
            onPressed: () => _confirmDelete(context, ref),
          ),
        ],
      ),
      body: async.when(
        loading: () => const Center(child: CircularProgressIndicator()),
        error: (e, _) => Center(
          child: Padding(
            padding: const EdgeInsets.all(24),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Text('$e', textAlign: TextAlign.center),
                const SizedBox(height: 12),
                FilledButton(
                  onPressed: () => ref.invalidate(parcelProvider(parcelId)),
                  child: const Text('Retry'),
                ),
              ],
            ),
          ),
        ),
        data: (p) => RefreshIndicator(
          onRefresh: () async {
            ref.invalidate(parcelProvider(parcelId));
            ref.invalidate(currentWeatherProvider(parcelId));
            ref.invalidate(forecastProvider(parcelId));
          },
          child: ListView(
            padding: const EdgeInsets.all(16),
            children: [
              _Row('Culture', p.culture?.nomCommun ?? '—'),
              _Row('Surface', p.surface != null ? '${p.surface} ha' : '—'),
              _Row('Status', p.status ?? '—'),
              _Row(
                  'Health score',
                  p.healthScore != null
                      ? '${p.healthScore!.toStringAsFixed(0)} / 100'
                      : '—'),
              _Row('Planted', _fmtDate(p.datePlantation)),
              _Row('Est. harvest', _fmtDate(p.dateRecolteEstimee)),
              _Row(
                  'Location',
                  (p.latitude != null && p.longitude != null)
                      ? '${p.latitude}, ${p.longitude}'
                      : '—'),
              const SizedBox(height: 16),
              Text('Weather',
                  style: Theme.of(context).textTheme.titleMedium),
              const SizedBox(height: 8),
              _WeatherSection(parcelId: parcelId),
              if (p.culture?.conseils != null &&
                  p.culture!.conseils!.isNotEmpty) ...[
                const SizedBox(height: 16),
                Text('Culture advice',
                    style: Theme.of(context).textTheme.titleMedium),
                const SizedBox(height: 8),
                Card(
                  child: Padding(
                    padding: const EdgeInsets.all(16),
                    child: Text(p.culture!.conseils!),
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
          loading: () => const Card(
            child: SizedBox(
              height: 100,
              child: Center(child: CircularProgressIndicator()),
            ),
          ),
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

class _Row extends StatelessWidget {
  const _Row(this.label, this.value);
  final String label;
  final String value;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 120,
            child: Text(label,
                style: Theme.of(context)
                    .textTheme
                    .bodyMedium
                    ?.copyWith(color: Theme.of(context).hintColor)),
          ),
          Expanded(child: Text(value)),
        ],
      ),
    );
  }
}
