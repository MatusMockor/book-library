# PHP Laravel Development Standards

## 1. Coding Standards

### Language & Framework
- **PHP Version:** 8.4 with latest language features
- **Laravel Version:** 12 with latest framework features
- **Design Principles:** SOLID principles throughout all code

### Code Quality
- **Style Guide:** Follow pint.json coding rules
- **Post-Modification:** Run pint after modifying files
- **Simplicity:** Clean code, keep it simple
- **Naming:** Use meaningful names for variables, methods, and classes
- **Complexity:** Avoid high code complexity
- **Conditionals:** Avoid using else in conditions
- **Comments:** Add only for exceptions, otherwise no comments unless specifically requested

### Performance
- **Algorithms:** Use effective, optimal, and high-performance algorithms
- **Database:** Use fast optimized DB queries

## 2. Project Structure & Architecture

### File Management
- **Version Control:** Delete .gitkeep when adding a file to a directory

### Database Interaction
- **Repository Pattern:** Use repositories to interact with a database
- **Query Style:** Avoid DB:: facade; use direct model methods (e.g., Model::where(), Model::find())

### Interface & Implementation
- **Interface Creation:** Create interfaces for all repositories and services
- **Interface Naming:** Format as `ClassNameInterface` (no "Interface" suffix)
- **Implementation Naming:** Format as `ClassName`
- **Repository Naming:** Format as `ClassNameRepository`
- **Registration:** Register interfaces in AppServiceProvider
- **Alias Naming:** Use "Contract" as the suffix for aliases
- **Binding Approach:** Register using class-based approach: `$this->app->bind(InterfaceContract::class, Implementation::class)`

### Controllers
- **Imports:** Use the `use` statement for all classes, including models

## 3. Directory Conventions

### Controllers
- **Location:** app/Http/Controllers
- **Structure:** No abstract/base controllers
- **Data Passing:** Avoid using compact() in Controllers

### Requests
- **Location:** app/Http/Requests
- **Validation:** Use FormRequest for validation
- **Naming:** Name with Create, Update, Delete prefixes

### Models
- **Location:** app/Models
- **Mass Assignment:** Use fillable property
- **Observers:** Use #[ObservedBy([ObserverClass::class])] attribute to register model observers

## 4. Testing

### Test Execution
- **Command:** Use Laravel Sail to run all tests: `./vendor/bin/sail test` or `./vendor/bin/sail artisan test`
- **Specific Files:** For specific test files: `./vendor/bin/sail test tests/path/to/TestFile.php`

### Test Requirements
- **Coverage:** All code must be tested
- **Test Preservation:** Don't remove tests without approval
- **Factories:** Generate a {Model}Factory with each model
- **Test Data:** Use laravel faker or fake() helper function instead of hardcoded values
- **Assertions:** When using assertDatabase, specify the table using Model::class

## 5. Test Directory Structure

### Organization
- **Console Tests:** tests/Feature/Console
- **Controller Tests:** tests/Feature/Http
- **Action Tests:** tests/Unit/Actions
- **Model Tests:** tests/Unit/Models
- **Job Tests:** tests/Unit/Jobs

## 6. Task Completion Requirements

### Quality Assurance
- **Completion:** All tasks must be completed
- **Testing:** All tasks must be tested
- **Review:** All tasks must be reviewed
- **Compliance:** Follow all rules before marking tasks complete