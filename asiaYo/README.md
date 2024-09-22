## About this project

Single Responsibility Principle: 每個類別都有單一的職責。例如，OrderRepository 只負責與訂單相關的資料操作，而不涉及其他業務邏輯。

Open/Closed Principle: OrderRepositoryFactory 可以在不修改現有程式的情況，加入新的貨幣資料庫類別。

Liskov Substitution Principle: 所有的OrderRepositoryInterface的實作都能被替換，且不會影響到客戶端的運行。例如，可以隨時替換 JPYOrderRepository 為 USDOrderRepository。

Interface Segregation Principle: 使用特定的Interface如 OrderRepositoryInterface，確保類別只依賴於它們實際使用的方法，避免大型接口造成的依賴。

Dependency Inversion Principle: 上層class（如控制器）不依賴於低層class（如資料庫實作），而是依賴於Interface。例如，控制器通過 OrderRepositoryInterface 與實作進行交互。

設計模式
Factory Pattern: 使用 OrderRepositoryFactory 創建不同類型的訂單資料庫，根據不同的貨幣選擇合適的類別，而不是直接使用。

## Start this project
docker-compose build
docker-compose up -d

## Close this project
docker-compose down