import 'package:equatable/equatable.dart';

/// Mirrors App\Http\Resources\UserResource.
class User extends Equatable {
  const User({
    required this.id,
    required this.username,
    required this.name,
    required this.email,
    this.role,
    this.region,
    this.experienceLevel,
    this.surfaceTotale,
    required this.isApproved,
    this.createdAt,
  });

  final int id;
  final String username;
  final String name;
  final String email;
  final String? role; // admin | agriculteur | technicien
  final String? region;
  final String? experienceLevel; // debutant | intermediaire | expert
  final double? surfaceTotale;
  final bool isApproved;
  final DateTime? createdAt;

  bool get isAdmin => role == 'admin';
  bool get isAgriculteur => role == 'agriculteur';
  bool get isTechnicien => role == 'technicien';

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'] as int,
      username: json['username'] as String? ?? '',
      name: json['name'] as String? ?? '',
      email: json['email'] as String? ?? '',
      role: json['role'] as String?,
      region: json['region'] as String?,
      experienceLevel: json['experience_level'] as String?,
      surfaceTotale: (json['surface_totale'] as num?)?.toDouble(),
      isApproved: json['is_approved'] as bool? ?? false,
      createdAt: json['created_at'] != null
          ? DateTime.tryParse(json['created_at'].toString())
          : null,
    );
  }

  @override
  List<Object?> get props => [id, username, email, role, isApproved];
}
