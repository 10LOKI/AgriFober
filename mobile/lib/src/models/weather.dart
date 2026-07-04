import 'package:equatable/equatable.dart';

/// Latest reading for a parcel, from GET /parcels/{id}/weather.
/// Mirrors App\Models\WeatherData (also matches the simulated fallback).
class CurrentWeather extends Equatable {
  const CurrentWeather({
    this.temp,
    this.humidity,
    this.precipitation,
    this.windSpeed,
    this.condition,
    this.source,
    this.recordedAt,
  });

  final double? temp;
  final double? humidity;
  final double? precipitation;
  final double? windSpeed;
  final String? condition;
  final String? source;
  final DateTime? recordedAt;

  bool get isSimulated => source == 'simulated';

  factory CurrentWeather.fromJson(Map<String, dynamic> json) {
    return CurrentWeather(
      temp: (json['temp'] as num?)?.toDouble(),
      humidity: (json['humidity'] as num?)?.toDouble(),
      precipitation: (json['precipitation'] as num?)?.toDouble(),
      windSpeed: (json['wind_speed'] as num?)?.toDouble(),
      condition: json['condition'] as String?,
      source: json['source'] as String?,
      recordedAt: json['recorded_at'] != null
          ? DateTime.tryParse(json['recorded_at'].toString())
          : null,
    );
  }

  @override
  List<Object?> get props =>
      [temp, humidity, precipitation, windSpeed, condition, recordedAt];
}

/// One day from GET /parcels/{id}/weather/forecast (5-day list).
class ForecastDay extends Equatable {
  const ForecastDay({
    required this.date,
    this.tempMax,
    this.tempMin,
    this.humidity,
    this.precipitationChance,
    this.condition,
  });

  final String date;
  final double? tempMax;
  final double? tempMin;
  final double? humidity;
  final int? precipitationChance;
  final String? condition;

  factory ForecastDay.fromJson(Map<String, dynamic> json) {
    return ForecastDay(
      date: json['date'] as String? ?? '',
      tempMax: (json['temp_max'] as num?)?.toDouble(),
      tempMin: (json['temp_min'] as num?)?.toDouble(),
      humidity: (json['humidity'] as num?)?.toDouble(),
      precipitationChance: (json['precipitation_chance'] as num?)?.toInt(),
      condition: json['condition'] as String?,
    );
  }

  @override
  List<Object?> get props => [date, tempMax, tempMin, condition];
}
