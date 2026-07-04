/// Normalised API error surfaced to controllers/UI.
///
/// Mirrors the Laravel responses:
/// - 422 validation -> { message, errors: { field: [..] } }
/// - 401 unauthenticated, 403 not approved / forbidden, etc.
class ApiException implements Exception {
  ApiException(
    this.message, {
    this.statusCode,
    this.errors,
  });

  final String message;
  final int? statusCode;

  /// Field-keyed validation errors from Laravel's 422 responses.
  final Map<String, List<String>>? errors;

  bool get isUnauthorized => statusCode == 401;
  bool get isForbidden => statusCode == 403;
  bool get isValidation => statusCode == 422;

  /// First validation message for [field], if any.
  String? firstErrorFor(String field) {
    final list = errors?[field];
    return (list != null && list.isNotEmpty) ? list.first : null;
  }

  @override
  String toString() => 'ApiException($statusCode): $message';
}
