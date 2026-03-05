1. Directory Structure (4 Layers with Actor Separation)
   You must strictly use and navigate this folder structure. Notice the separation of Admin and Client in the Application and Interfaces layers, while Domain and Infrastructure remain shared.

Plaintext
app/
├── Domain/ # SHARED CORE: Pure PHP, no Laravel dependencies
│ ├── Entities/ # Pure PHP classes containing domain logic and state
│ ├── Enums/ # State, Status, Types (e.g., OrderStatusEnum)
│ ├── ValueObjects/ # Immutable concepts
│ ├── Exceptions/ # Domain-specific exceptions
│ └── Repositories/ # Repository INTERFACES only (Contracts)
│
├── Application/ # USE CASES: Separated by Actor
│ ├── Admin/ # Admin-specific logic
│ │ ├── UseCases/ # e.g., CreateTourUseCase, ApproveReviewUseCase
│ │ └── DTOs/ # e.g., CreateTourDTO
│ │
│ └── Client/ # Client/Public-specific logic
│ ├── UseCases/ # e.g., SearchTourUseCase, BookHotelUseCase
│ └── DTOs/ # e.g., SearchTourDTO
│
├── Infrastructure/ # SHARED EXTERNAL: DB, Framework, Third-party
│ ├── Database/
│ │ ├── Models/ # ELOQUENT MODELS: ONLY for DB mapping and Relationships
│ │ └── Repositories/ # Eloquent implementations of Domain Repository Interfaces
│ └── Services/ # External integrations (Payment, Email)
│ ├── Contracts/ # Service Interfaces
│ └── External/ # Concrete Service Implementations
│
└── Interfaces/ # PRESENTATION: Separated by Actor
└── Http/
├── Admin/ # Admin APIs
│ ├── Controllers/ # e.g., AdminTourController
│ ├── Requests/ # e.g., StoreTourRequest
│ └── Resources/ # Exposes internal/full data
│
└── Client/ # Client APIs
├── Controllers/ # e.g., PublicTourController
├── Requests/ # e.g., SearchTourRequest
└── Resources/ # Hides sensitive data, exposes only public fields 2. Actor Separation Rules (Admin vs. Client)
Domain & Infrastructure Layers: These are strictly SHARED. Do not create Admin/Client subfolders here. A Tour entity or model is exactly the same concept regardless of who accesses it.

Application & Interfaces Layers: Must be strictly separated by actor namespace (Admin vs. Client).

Data Exposure: Never mix Admin Resources with Client Resources. Client API Resources must strictly hide sensitive/internal database fields.

3. Strict Dependency Rules (Dependency Inversion)
   Flow: Controller → DTO → UseCase → Repository Interface (Resolved via DI).

Forbidden Flows:

Controllers MUST NOT call Repositories or Models directly.

Controllers MUST NOT contain business logic.

UseCases MUST NOT depend on concrete Repositories or Services (Depend on Interfaces instead).

Services MUST NOT call Repositories.

3. Entity vs. Eloquent Model Separation (CRITICAL)
   Eloquent Models (Infrastructure/Database/Models): MUST ONLY contain database configuration ($table, $fillable) and relationship definitions (hasMany, belongsTo). Do NOT put business logic or custom getters/setters here. Examples like public function isEnable() must not be here.

Entities (Domain/Entities): MUST be pure PHP classes. They contain the actual domain logic, state validation, and behavior. Repositories must map Eloquent Models to Domain Entities before returning data to UseCases.

4. Architecture Responsibilities
   Controllers: Parse HTTP request, validate via FormRequest, map to DTO, execute UseCase, and return Laravel API Resource. No if/else business logic.

UseCases: Execute exactly ONE business action (e.g., BookHotelUseCase, SearchTourUseCase). Contains the core application workflow.

Repositories: Handle ONLY data persistence and retrieval. Must implement a Domain Interface.

Services: Handle ONLY external infrastructure (e.g., Stripe, SendGrid). Must implement an Interface.

5. Coding Standards & Quality
   No Magic Values: ALWAYS use Enums for statuses/types and Constants for fixed values (limits, default settings).

Bad: if ($status == 1)

Good: if ($status === StateActiveEnum::ENABLE)

Naming Conventions:

Classes/Interfaces: PascalCase

Variables/Methods: camelCase

Database columns: snake_case

Error Handling: Never return generic or raw exceptions to the controller. Use Domain Exceptions (e.g., RoomUnavailableException).

API Responses: Always use Laravel Resources to format responses uniformly. Do not return raw arrays from Controllers.

6. Performance & Security
   Solve N+1 queries using eager loading (with()) in Repositories.

Always use DB Transactions inside UseCases when modifying multiple records.

Never trust client input; always validate via FormRequests before converting to DTOs.
