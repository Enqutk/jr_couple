# Contact System Architecture

## Overview
This document describes the refactored contact system that follows Laravel best practices and modern architectural patterns.

## Architecture Layers

### 1. Routes (`routes/web.php`)
```php
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact/send/{recipient}', [ContactController::class, 'send'])->name('contact.send');
```
- **Clean and simple** route definitions
- **Controller-based** routing instead of closures
- **Named routes** for easy reference

### 2. Controller (`app/Http/Controllers/ContactController.php`)
```php
class ContactController extends Controller
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    public function index()
    {
        $data = $this->contactService->getContactData();
        return view('contact', compact('data'));
    }
}
```
- **Thin controllers** - only handle HTTP requests/responses
- **Dependency injection** for services
- **Single responsibility** - each method has one purpose

### 3. Service Layer (`app/Services/ContactService.php`)
```php
class ContactService
{
    protected $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function getContactData(): array
    {
        // Business logic for formatting contact data
    }
}
```
- **Business logic** handling
- **Data transformation** and formatting
- **Default values** and fallbacks
- **Reusable** across different parts of the application

### 4. Repository Layer (`app/Repositories/ContactRepository.php`)
```php
class ContactRepository
{
    public function getOrganization(): ?Organization
    {
        return Organization::first();
    }

    public function getActiveContacts()
    {
        return OrganizationContact::where('status', StatusEnum::active)->get();
    }
}
```
- **Data access logic** abstraction
- **Database queries** isolation
- **Easy to test** and mock
- **Reusable** data access methods

### 5. Models (`app/Models/`)
- **Organization** - Company information, address, working hours
- **OrganizationContact** - Contact methods (phone, email, fax)

## Benefits of This Architecture

### ✅ **Separation of Concerns**
- **Routes**: Only define URL patterns
- **Controllers**: Handle HTTP logic
- **Services**: Handle business logic
- **Repositories**: Handle data access
- **Models**: Handle data structure

### ✅ **Testability**
- Each layer can be **unit tested** independently
- **Dependency injection** makes mocking easy
- **Clear interfaces** between layers

### ✅ **Maintainability**
- **Single responsibility** principle
- **Easy to modify** without affecting other layers
- **Clear dependencies** between components

### ✅ **Reusability**
- **Services** can be used by multiple controllers
- **Repositories** can be used by multiple services
- **Business logic** is centralized

### ✅ **Scalability**
- **Easy to add** new contact methods
- **Easy to modify** data sources
- **Easy to extend** functionality

## Data Flow

```
Route → Controller → Service → Repository → Model → Database
  ↓         ↓         ↓         ↓         ↓
View ← Controller ← Service ← Repository ← Model
```

## Usage Examples

### Getting Contact Data
```php
// In a controller
$contactService = app(ContactService::class);
$data = $contactService->getContactData();

// In a view
$data['email']     // Array of email addresses
$data['phone']     // Array of phone numbers
$data['address']   // Company address
$data['working_days'] // Working hours
$data['map']       // Google Maps embed URL
```

### Adding New Contact Types
```php
// 1. Add to OrganizationContact model types
public static function getTypeOptions(): array
{
    return [
        'phone' => 'Phone',
        'fax' => 'Fax',
        'email' => 'Email',
        'whatsapp' => 'WhatsApp', // New type
    ];
}

// 2. Add to ContactService
'whatsapp' => $this->getContactsByType($contacts, 'whatsapp'),

// 3. Add default values
private function getDefaultContacts(string $type): array
{
    $defaults = [
        'email' => ['contact@veritasafrika.com'],
        'phone' => ['+27 11 123 4567'],
        'fax' => ['+27 11 123 4568'],
        'whatsapp' => ['+27 82 123 4567'], // New default
    ];
    return $defaults[$type] ?? [];
}
```

## Best Practices Implemented

1. **SOLID Principles**
   - Single Responsibility
   - Open/Closed
   - Dependency Inversion

2. **Laravel Conventions**
   - Proper namespacing
   - Service container usage
   - Route model binding

3. **Clean Code**
   - Descriptive method names
   - Proper documentation
   - Consistent formatting

4. **Error Handling**
   - Fallback values
   - Null safety
   - Graceful degradation

## Testing

### Unit Tests
```php
// Test ContactService
public function test_get_contact_data_returns_correct_structure()
{
    $service = new ContactService($this->mockRepository);
    $data = $service->getContactData();
    
    $this->assertArrayHasKey('email', $data);
    $this->assertArrayHasKey('phone', $data);
    $this->assertArrayHasKey('address', $data);
}
```

### Feature Tests
```php
// Test contact page loads
public function test_contact_page_loads_with_data()
{
    $response = $this->get('/contact');
    
    $response->assertStatus(200);
    $response->assertViewHas('data');
}
```

## Future Enhancements

1. **Caching** - Cache contact data for performance
2. **API Endpoints** - Expose contact data via API
3. **Multi-language** - Support multiple languages
4. **Contact Forms** - Multiple form types
5. **Analytics** - Track contact form submissions

---

This architecture provides a solid foundation for a maintainable and scalable contact system that follows Laravel best practices.
