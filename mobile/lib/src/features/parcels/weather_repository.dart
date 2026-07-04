import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../core/api_client.dart';
import '../../core/providers.dart';
import '../../models/weather.dart';

final weatherRepositoryProvider = Provider<WeatherRepository>((ref) {
  return WeatherRepository(ref.watch(apiClientProvider));
});

/// GET /parcels/{id}/weather and /parcels/{id}/weather/forecast.
class WeatherRepository {
  WeatherRepository(this._api);
  final ApiClient _api;

  /// Envelope data is { parcel, location, weather } — we only need weather.
  Future<CurrentWeather> current(int parcelId) async {
    final data = await _api.get('/parcels/$parcelId/weather');
    return CurrentWeather.fromJson(
      (data as Map<String, dynamic>)['weather'] as Map<String, dynamic>,
    );
  }

  /// Envelope data is { parcel, forecast: [...] }.
  Future<List<ForecastDay>> forecast(int parcelId) async {
    final data = await _api.get('/parcels/$parcelId/weather/forecast');
    return ((data as Map<String, dynamic>)['forecast'] as List)
        .map((e) => ForecastDay.fromJson(e as Map<String, dynamic>))
        .toList();
  }
}

final currentWeatherProvider =
    FutureProvider.autoDispose.family<CurrentWeather, int>((ref, parcelId) {
  return ref.watch(weatherRepositoryProvider).current(parcelId);
});

final forecastProvider =
    FutureProvider.autoDispose.family<List<ForecastDay>, int>((ref, parcelId) {
  return ref.watch(weatherRepositoryProvider).forecast(parcelId);
});
