# hex-arh-laravel

## ✅ ЗАДАНИЕ 1. Инициализация проекта и базовая аутентификация

**Цель:** создать минимальное рабочее окружение с аутентификацией.

### Шаги:

1. Установи Laravel:

   ```bash
   laravel new todo-hex
   cd todo-hex
   ```
2. Установи Laravel Breeze:

   ```bash
   composer require laravel/breeze --dev
   php artisan breeze:install
   npm install && npm run dev
   php artisan migrate
   ```
3. Убедись, что логин и регистрация работают.
4. Инициализируй репозиторий Git, сделай первый коммит:

   ```bash
   git init
   git add .
   git commit -m "Init Laravel project with Breeze"
   ```

📘 **Результат:** Laravel-проект с регистрацией, логином и миграциями.

---

## ✅ ЗАДАНИЕ 2. Базовая структура гексагональной архитектуры

**Цель:** подготовить скелет проекта под hex-архитектуру (Ports & Adapters).

### Шаги:

1. Удали `app/Models/Task.php` если она создастся позже по привычке. Мы всё пишем вручную.

2. Создай структуру:

   ```
   app/
   ├── Domain/
   │   ├── Entities/
   │   ├── ValueObjects/
   │   ├── Interfaces/
   ├── Application/
   │   ├── UseCases/
   │   ├── DTOs/
   ├── Infrastructure/
   │   ├── Persistence/
   │   ├── Mail/
   ├── UI/
   │   ├── Web/
   │   │   ├── Controllers/
   │   │   ├── Requests/
   │   │   ├── Views/
   ├── Providers/
   ```

3. Создай файл `ARCHITECTURE.md` с пояснением структуры (что за слой, кто кому зависит).

4. В `.gitignore` добавь `*.drawio` — сюда будем сохранять схемы архитектуры.

📘 **Результат:** Чистая, модульная архитектурная база, готовая к росту.

---

## ✅ ЗАДАНИЕ 3. Доменные сущности и value-объекты

**Цель:** создать сущность Task и value-object для статуса задачи.

### Шаги:

1. Создай `app/Domain/Entities/Task.php`:

   ```php
   class Task
   {
       public function __construct(
           public readonly int|null $id,
           public string $title,
           public TaskStatus $status,
           public int $userId,
           public ?\DateTimeImmutable $dueDate = null,
       ) {}
   }
   ```

2. Создай value object `TaskStatus` в `Domain/ValueObjects/TaskStatus.php`:

   ```php
   final class TaskStatus
   {
       public const PENDING = 'pending';
       public const COMPLETED = 'completed';

       public function __construct(private string $value)
       {
           if (!in_array($value, [self::PENDING, self::COMPLETED])) {
               throw new InvalidArgumentException("Invalid status: {$value}");
           }
       }

       public function value(): string
       {
           return $this->value;
       }

       public function isCompleted(): bool
       {
           return $this->value === self::COMPLETED;
       }
   }
   ```

3. Напиши unit test на TaskStatus.

📘 **Результат:** У тебя есть чистый доменный слой, независимый от Laravel.

---

## ✅ ЗАДАНИЕ 4. Репозитории и интерфейсы

**Цель:** определить поведение слоя данных.

### Шаги:

1. В `Domain/Interfaces/TaskRepositoryInterface.php`:

   ```php
   interface TaskRepositoryInterface
   {
       public function save(Task $task): void;
       public function findById(int $id): ?Task;
       public function findByUser(int $userId): array;
   }
   ```

2. В `Domain/Interfaces/TaskServiceInterface.php` — если нужен сервисный слой позже.

📘 **Результат:** Домен не знает об инфраструктуре. Контракты — только интерфейсы.

---

## ✅ ЗАДАНИЕ 5. Application Layer: Use Cases

**Цель:** реализация бизнес-кейсов.

### Шаги:

1. В `Application/UseCases/AddTaskUseCase.php`:

   ```php
   class AddTaskUseCase
   {
       public function __construct(private TaskRepositoryInterface $repository) {}

       public function execute(CreateTaskDto $dto): void
       {
           $task = new Task(
               id: null,
               title: $dto->title,
               status: new TaskStatus(TaskStatus::PENDING),
               userId: $dto->userId,
               dueDate: $dto->dueDate
           );
           $this->repository->save($task);
       }
   }
   ```

2. Создай `CreateTaskDto` в `Application/DTOs`.

📘 **Результат:** Use case без зависимостей от Laravel, работающий с чистыми объектами.

---

## ✅ ЗАДАНИЕ 6. Eloquent реализация репозитория

**Цель:** адаптер из Eloquent в домен.

### Шаги:

1. Создай `app/Infrastructure/Persistence/EloquentTask.php` (модель):

   ```php
   class EloquentTask extends Model
   {
       protected $fillable = ['title', 'status', 'user_id', 'due_date'];
   }
   ```

2. Реализуй `EloquentTaskRepository`:

   ```php
   class EloquentTaskRepository implements TaskRepositoryInterface
   {
       public function save(Task $task): void { /* map to Eloquent */ }
       public function findById(int $id): ?Task { /* map from Eloquent */ }
       public function findByUser(int $userId): array { /* map from Eloquent */ }
   }
   ```

📘 **Результат:** Репозиторий с Eloquent, не трогающий доменные классы.

---

## ✅ ЗАДАНИЕ 7. Регистрация привязок в контейнере

**Цель:** связать контракты с реализациями.

### Шаги:

1. Создай `App\Providers\DomainServiceProvider.php`:

   ```php
   public function register()
   {
       $this->app->bind(TaskRepositoryInterface::class, EloquentTaskRepository::class);
   }
   ```

2. Зарегистрируй провайдер в `config/app.php`.

📘 **Результат:** DI работает, Laravel не знает ничего о слоях.

---

## ✅ ЗАДАНИЕ 8. Контроллеры и формы

**Цель:** реализовать UI без бизнес-логики.

### Шаги:

1. Создай `TaskController` в `UI/Web/Controllers`.
2. В метод `store` передай `AddTaskUseCase`, вызови `execute()` с DTO.
3. В Request-классе — валидация.

📘 **Результат:** Контроллер работает как glue-код: UI → UseCase.

---

## ✅ ЗАДАНИЕ 9. Миграции и запуск

**Цель:** реализовать структуру БД.

### Шаги:

1. Создай миграцию:

   ```php
   Schema::create('tasks', function (Blueprint $table) {
       $table->id();
       $table->string('title');
       $table->string('status');
       $table->unsignedBigInteger('user_id');
       $table->dateTime('due_date')->nullable();
       $table->timestamps();
   });
   ```

2. Выполни:

   ```bash
   php artisan migrate
   ```

📘 **Результат:** структура синхронизирована с доменом.

---

## ✅ ЗАДАНИЕ 10. Листинг и завершение задач

**Цель:** реализовать чтение и завершение задач.

### Шаги:

1. Создай `ListTasksUseCase` и `CompleteTaskUseCase` аналогично `AddTaskUseCase`.
2. В UI вызови нужный use-case, передай параметры.
3. Сделай blade-шаблон с выводом задач и кнопкой "Завершить".

📘 **Результат:** Задачи отображаются, можно завершать.

---

## ✅ ЗАДАНИЕ 11. API (опционально)

**Цель:** вынести интерфейс на REST.

### Шаги:

1. Создай `api.php` роуты:

   ```php
   Route::middleware('auth:sanctum')->get('/tasks', [TaskApiController::class, 'index']);
   ```

2. В контроллере вызывай `ListTasksUseCase`, возвращай JSON.

📘 **Результат:** API использует те же use-cases, UI не важен.

---

## ✅ ЗАДАНИЕ 12. Тесты

**Цель:** покрыть бизнес-логику и контроллеры тестами.

### Шаги:

1. `php artisan make:test AddTaskUseCaseTest`
2. Мокаешь TaskRepositoryInterface, проверяешь вызов `save()`
3. То же самое для других use-cases

📘 **Результат:** Уверенность в логике, покрытие use cases.

---

Хочешь, могу сделать шаблон репозитория с этими слоями, документацией и Makefile.
**Следующий шаг:** начни с Задания 1. Сделаешь — покажи структуру и я продолжу менторить.
