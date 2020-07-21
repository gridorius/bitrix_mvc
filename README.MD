![Иллюстрация к проекту](https://aviasovet.ru/blog/wp-content/uploads/2014/06/%D0%A4%D0%BE%D1%82%D0%BE-%E2%84%96-7.jpg)
#Мы Не Утонем
#Не марай руки!

local/GRA - туда не лезь

App - корень приложения
App/Cached - кеш шаблонизатора
App/Controllers - контроллеры
App/Models - модели
App/Routes - роутинг
App/Views - шаблоны(представления)

class Model{
protected static $iblockClass - Имя класса модели(основной класс, с которым работает модель)(по дефолту CIBlock)
protected static $selectMethodName - Имя метода для получения списка(по дефолту GetList)

// устанавливает параметры фильтрации в виде ('ID=2', 'PROPERTY_PRICE>=20')
// возвращает саму модель, можной чейнить
// можно вызывать множество цепочек where $model->where('ID=2')->where('PROPERTY_PRICE>=20')
public function where():Model

// делает тоже самое, что и where, но отчищает пред идущий массив фильтрации
public function whereClear():Model

// для более мошьной фильтрации, также как и в самом битриксе $model->whereRaw(['=ID' => 2, ['LOGIC' => 'AND', ...])
public function whereRaw():Model

// устанавливает запрашиваемые поля $model->select('ID', 'NAME')
public function select():Model

// делает тоже самое, что и select, но отчищает пред идущий массив выборки
public function clearSelect():Model

// при вызове данной функции устанавливается пагинация
public function pagination(int $pageNum, int $pageSize):Model

// Возвращает количество записей в ответе, ТОЛЬКО ПОСЛЕ ВЫЗОВА $model->get()
public function getCount():int

// выполняет запрос к базе, записывает данные в $model->result
public function get():Model

// Возвращает массив обьектов класса Model, ТОЛЬКО ПОСЛЕ ВЫЗОВА $model->get()
public function getArray():array

// Функция для переопределения в дочерних моделях, либо для сырых запросов
public function getResult():CDBResult
}



