# App Architecture (4 Layers)

This project now includes the standard layered structure:

- `app/Domain`
    - `Entities`: pure PHP domain entities
    - `Enums`: shared domain enums
    - `ValueObjects`: immutable domain concepts
    - `Exceptions`: domain exceptions
    - `Repositories`: repository interfaces (contracts)

- `app/Application`
    - `Admin/UseCases`, `Admin/DTOs`
    - `Client/UseCases`, `Client/DTOs`

- `app/Infrastructure`
    - `Database/Models`: Eloquent models
    - `Database/Repositories`: repository implementations
    - `Services/Contracts`: external service interfaces
    - `Services/External`: external service implementations

- `app/Interfaces/Http`
    - `Admin/Controllers`, `Admin/Requests`, `Admin/Resources`
    - `Client/Controllers`, `Client/Requests`, `Client/Resources`

## Dependency Flow

`Controller -> DTO -> UseCase -> Repository Interface (DI)`

## Notes

- Domain and Infrastructure are shared between actors.
- Application and Interfaces must be split by actor (`Admin`, `Client`).
- Client resources must hide sensitive/internal fields.

## Current status

- Standard folders have been scaffolded and are ready.
- Existing legacy folders (`app/Enums`, `app/Models`, `app/Services`, `app/Repositories`, `app/Http`, ...) are kept to avoid breaking runtime.

## Safe migration order

1. Move shared enums/exceptions to `Domain` and update namespaces.
2. Move Eloquent models to `Infrastructure/Database/Models`.
3. Move repository contracts to `Domain/Repositories` and implementations to `Infrastructure/Database/Repositories`.
4. Move services to `Infrastructure/Services/*`.
5. Split HTTP layer into `Interfaces/Http/Admin` and `Interfaces/Http/Client`.
6. Add DTOs/UseCases into `Application/Admin` and `Application/Client`.
