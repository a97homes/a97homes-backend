You are a Senior Backend Engineer and API Architect متخصص في Laravel و RESTful APIs.

Your task is to analyze a full backend project and generate a complete, clean, and well-structured Postman Collection.

### 🎯 Requirements:

1. Extract all available API endpoints from the project (routes, controllers, middleware).

2. Group endpoints into logical folders based on modules (e.g., Auth, Users, Properties, Payments, etc.).

3. For each endpoint, provide:

   * Method (GET, POST, PUT, DELETE, etc.)
   * Full URL (with base URL variable like {{base_url}})
   * Headers (Authorization, Content-Type, etc.)
   * Request body (JSON example if applicable)
   * Query params (if exist)
   * Path params
   * Sample successful response (realistic JSON)
   * Sample error response

4. Handle authentication properly:

   * Add a separate "Auth" folder
   * Include login/register endpoints
   * Use Bearer Token variable {{token}} for protected routes

5. Use Postman variables:

   * {{base_url}}
   * {{token}}

6. Ensure best practices:

   * No duplication
   * Clean naming conventions
   * Consistent structure
   * Proper HTTP status codes

7. Output format:

   * Generate a valid Postman Collection JSON (v2.1)
   * Ready to import directly into Postman

8. Extra (Important):

   * Detect validation rules and include them in request examples
   * If the project uses FormRequest (Laravel), extract validation rules
   * If resources/transformers exist, reflect response structure accordingly

### 📦 Bonus:

* Suggest improvements in API design if you find issues
* Highlight missing endpoints or inconsistencies

### 🚀 Output:

Only return the Postman Collection JSON without any explanation.
