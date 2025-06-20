<?php
/**
 * This file is part of the mgrechanik/yii2-book-catalog project
 *
 * @copyright Copyright (c) Mikhail Grechanik <mike.grechanik@gmail.com>
 * @license https://github.com/mgrechanik/yii2-book-catalog/blob/main/LICENSE.md
 * @link https://github.com/mgrechanik/yii2-book-catalog
 */
declare(strict_types=1);

namespace app\models\forms;

use Yii;
use app\models\entities\Book;
use app\models\entities\Author;
use app\services\BookImageServiceInterface;

/**
 *
 */
class BookCreateForm extends \yii\base\Model
{
    /**
     * Сколько максимально авторов мы показываем на формах создания/удаления книги
     */
    public const AUTHORS_MAX_AMOUNT = 3;

    /**
     * @var string Название книги
     */
    public string $name = '';

    /**
     * @var string|null Jgbcfybt rybub
     */
    public ?string $description = '';

    /**
     * @var string|null ISBN книги.
     */
    public ?string $isbn = '';

    /**
     * @var int Год издания
     */
    public int $year = 0;

    /**
     * @var Загружаемая картинка
     */
    public $imageFile;

    /**
     * @var string Относительный путь к картинке
     */
    public string $imagePath = '';

    /**
     * @var int Пользователь, создавший книгу
     */
    public int  $id_user;

    /**
     * @var array Авторы
     * Массив типа
     * ['author1' => 1, 'author2' => 4, ...], который через магический __get позволяем у модели динамически добавить св-ва
     * $book->author1,
     * $book->author2
     * ...
     * $book->authorN  , где N - self:: AUTHORS_MAX_AMOUNT
     */
    public array $authors = [];

    /**
     * @var BookImageServiceInterface  Сервис управления картинками
     */
    private BookImageServiceInterface $imgService;


    public function __construct(BookImageServiceInterface $imgService, $config = [])
    {
        parent::__construct($config);
        $this->imgService = $imgService;
    }

    public function init()
    {
        $this->id_user = Yii::$app->user->identity->id;
        $this->year = (int) date("Y");

        for ($i = 1; $i <= self::AUTHORS_MAX_AMOUNT; $i++) {
            $this->authors['author' . $i] = '';
        }

        parent::init();
    }

    public function attributes()
    {
        $attrs =  parent::attributes();
        foreach ($this->authors as $key => $val) {
            $attrs[] = $key;
        }
        return $attrs;
    }

    public function __get($name)
    {
        if (str_starts_with($name, 'author')) {
            if (isset($this->authors[$name])) {
                return $this->authors[$name];
            }
        }
        return parent::__get($name);
    }


    public function __set($name, $value)
    {
        if (str_starts_with($name, 'author')) {
            if (isset($this->authors[$name])) {
                $this->authors[$name] = $value;
                return $this->authors[$name];
            }
        }
        parent::__set($name, $value);
    }

    public function rules()
    {
        $res = [
            [['description', 'isbn'], 'default', 'value' => null],
            [['name'], 'required'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 100],
            [['isbn'], 'string', 'max' => 17],
            [['isbn'], 'match', 'pattern' => '/^(\d){3}-(\d){1}-(\d){3}-(\d){5}-(\d){1}$/'],
            [['year'], 'match', 'pattern' => '/^\d{4}$/'],
            [['year'], 'number', 'min' => 1900, 'max' => intval(date('Y')) + 1],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            // Нужно будет переопределить в форме редактирования
            'isbn' => [['isbn'], 'unique', 'targetClass' => Book::class],
            [['author1'], 'checkAuthors', 'skipOnEmpty' => false],
        ];

        foreach ($this->authors as $key => $val) {
            $res[] = [[$key], 'exist', 'targetClass' => Author::class, 'targetAttribute' => 'id'];
        }

        return $res;
    }

    public function checkAuthors($attribute, $params, $validator)
    {
        $existed = [];
        $hasAuthor = false;
        foreach ($this->authors as $author) {
            if ($author) {
                $hasAuthor = true;
                if (isset($existed[$author])) {
                    $this->addError($attribute, 'Авторы не должны повторяться');
                } else {
                    $existed[$author] = true;
                }
            }
        }
        if (!$hasAuthor) {
            $this->addError($attribute, 'Вы должны указать хотя бы одного автора');
        }
    }

    /**
     * Загрузка картинки
     * @return void
     */
    public function upload(): void
    {
        if ($this->imageFile) {
            $this->imagePath = $this->imgService->save($this->imageFile);
        }
    }

    /**
     * Id-шки авторов данной книги
     * @return int[]
     */
    public function getAuthorIds(): array
    {
        return array_map('intval', array_filter(array_values($this->authors)));
    }
}
