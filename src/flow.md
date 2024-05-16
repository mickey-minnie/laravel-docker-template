## 一覧表示・新規作成フロー

### 初期画面への遷移と一覧表示
- 初期画面のURIが/todoで、メソッドとしては何かを送信しているわけではなく、表示のリクエストを送っているだけなので、メソッドがGETになり、
  web.phpの<br>
  Route::get('/todo', 'TodoController@index')->name('todo.index)<br>
  に入る。この時、/todoの場合はTodoControllerクラスのindexメソッドに入るので、TodoControllerクラスに入る
- indexメソッドを確認（Controllerクラスを継承の記述、Controllerクラスではuseでクラスを使えるように宣言）
  indexメソッドでは、$todo に Todo Modelをインスタンス化して、
  Todo Modelオブジェクトのallメソッドを実行することで、SELECT * FROM todosのSQL文実行
  [インデックス番号] => App\Todo　の連想配列が格納される
    - （ToDOクラス　protected：そのクラス自身と継承クラスからアクセス可能で、非公開だが、継承は可能　スコープを狭める場合に使用
      $table にDBの'todos'テーブルを格納
      $fillable = 'content'とすることで、ToDoリストに入力されるプロパティの値がcontentに限定される＝悪意のある攻撃の防御）
- 返り値がviewの第一引数が'todo.index'なので、これを画面に表示であり、第二引数では ['todoList' => $todoList]なので、
  $todoListという変数名で上記の$todoList（[インデックス番号]=>App\Todoの連想配列）を渡す
- todo.indexを確認すると、@extends('layouts.base')の記述があるので、layouts.baseの@yield('content')までの部分を継承していて、
  以降は@section('content')〜@endsectionの部分が挿入されて表示されている
  ToDo一覧の部分の表記を確認すると、
  @foreach ($todoList as $todo)
    <div class="d-flex align-items-center p-2">
      <span class="col-9">{{ $todo->content }}</span>
    </div>
  @endforeachの記載があるので、先ほどの$todoList（[インデックス番号]=>App\Todoの連想配列）as $todoなので、$todoListの連想配列から、
  データ１個分の連想配列がバリューとして取り出され、さらに$todoの連想配列からcontentのカラムのみを抽出する処理をforeachで繰り返しているので、ユーザーが入力したコンテンツのみが一覧表示でtodo.indexに表示されている

### 新規作成
- ToDoを追加ボタンを押下すると、/createページに遷移するのですが、これはweb.phpの、メソッドとしては何かを送信しているわけではなく、表示の
  リクエストを送っているだけなので、メソッドがGETになり、<br>
  Route::get('/todo/create', 'TodoController@create')->name('todo.create');<br>
  に入る。この時、/todo/createの場合はTodoControllerクラスのcreateメソッドに入るので、TodoControllerクラスに入る
- createメソッドを確認すると、返り値はview('todo.create'); なので、第一引数の'todo.create'を画面に表示する
- この時もtodo.createを確認すると、@extends('layouts.base')の記述があるので、layouts.baseの@yield('content')までの部分を継承して
  いて、以降は@section('content')〜@endsectionの部分が挿入されて表示されている
  このページには<input type="text" class="form-control" name="content" value="">のタグがあり、ここにユーザーが値を入力して、type="submit"の作成ボタンを押下することで、<form method="POST" action="{{ route('todo.store') }}">の部分の、POSTメソッドで、
  actionに記載のあるrouteメソッドでtodo.storeに遷移する　これはweb.phpを確認すると、name('todo.store');の記載があり、それは
  Route::post('/todo', 'TodoController@store');なので、今回、値を入力してPOSTしているのでPOSTメソッドで、TodoControllerクラスのstoreメソッドに入るので、確認する
- storeメソッドを確認すると、引数のところで、Requestをインスタンス化して、$requestに代入している
  Requestインスタンスのall()メソッドでフォームから送信されたデータを全件連想配列で取得し、$inputsに格納
  $todoにTodoモデルのインスタンス化を格納し、Todoモデルのインスタンス化から引数に全件の連想配列を持った$inputsを渡しながらfillメソッドで
  引数の$inputsの連想配列を全て一括で代入する
  最後にTodoモデルのインスタンス化からsaveメソッドでINSERT INTO todos (content) VALUES ('入力された値')のSQL文が実行されて、todosテーブルに値が格納される
  返り値はredirect()->route('todo.index');なので、todo.indexにリダイレクトして、この時web.phpの
  Route::get('/todo', 'TodoController@index')->name('todo.index');
  を通るので、TodoControllerクラスのindexメソッドを確認すると、
- indexメソッドでは、$todo に Todo Modelをインスタンス化して、
  Todo Modelオブジェクトのallメソッドを実行することで、SELECT * FROM todosのSQL文実行
  [インデックス番号] => App\Todo　の連想配列が格納される
- 返り値がviewの第一引数が'todo.index'なので、これを画面に表示であり、第二引数では ['todoList' => $todoList]なので、
  $todoListという変数名で上記の$todoList（[インデックス番号]=>App\Todoの連想配列）を渡す
- todo.indexを確認すると、@extends('layouts.base')の記述があるので、layouts.baseの@yield('content')までの部分を継承していて、
  以降は@section('content')〜@endsectionの部分が挿入されて表示されている
  ToDo一覧の部分の表記を確認すると、
  @foreach ($todoList as $todo)
    <div class="d-flex align-items-center p-2">
      <span class="col-9">{{ $todo->content }}</span>
    </div>
  @endforeachの記載があるので、先ほどの$todoList（[インデックス番号]=>App\Todoの連想配列）as $todoなので、$todoListの連想配列から、
  データ１個分の連想配列がバリューとして取り出され、さらに$todoの連想配列からcontentのカラムのみを抽出する処理をforeachで繰り返しているので、ユーザーが入力したコンテンツのみが一覧表示でtodo.indexに表示されている

