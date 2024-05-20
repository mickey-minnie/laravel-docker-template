## 一覧表示・新規作成フロー

### 初期画面への遷移と一覧表示
- 初期画面のURIが/todoで、メソッドとしては何かを送信しているわけではなく、表示のリクエストを送っているだけなので、メソッドがGETになり、
  web.phpの<br>
  Route::get('/todo', 'TodoController@index')->name('todo.index)<br>
  に入る。この時、/todoの場合はTodoControllerクラスのindexメソッドに入るので、TodoControllerクラスに入る
- indexメソッドを確認（Controllerクラスを継承の記述、Controllerクラスではuseでクラスを使えるように宣言）
  indexメソッドでは、$todo に Todo Modelをインスタンス化して、
  Todo Modelオブジェクトのallメソッドを実行することで、SELECT * FROM todosのSQL文実行
  Collectionクラスのインスタンス化が格納される（連想配列でレコードが入っている（削除実装後はdeleted_atがNULLのもののみ））
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
  Collectionクラスのインスタンス化が格納される
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

## 更新処理・削除処理

### 更新処理
- 初期画面から詳細ボタンを押下すると、詳細画面に遷移する。
これは、メソッドとしては何かを送信しているわけではなく、表示のリクエストを送っているだけなので、メソッドがGETになり、web.phpの<br>
Route::get('/todo/{id}', 'TodoController@show')->name('todo.show');　に入る。
これは、第一引数が/todo/{id}というURIで変数idの部分はルートパラメータという。ほしい情報のidを指定することで、一意の値が定まって取得できる。この場合は第二引数のTodoControllerクラスのshowメソッドに入るので、TodoControllerクラスに入る
- showメソッドを確認すると、引数にidを持っていて、$todoにtodoモデルのインスタンス化を格納して、findメソッドで、主キーのidの変更前のレコードを取得する
返り値は、todo.showに遷移しつつ、$todoの値をtodo.showのtodoに渡す
- todo.showに遷移すると詳細画面が表示される

- 更新処理の場合は、
編集するボタンを押下すると、aタグのリンクになっているので、route関数でtodo.editに遷移しながら、第二引数に記載のある、idのレコードをtodo.editの$todoに渡す。
web.phpを確認すると、Route::get('/todo/{id}/edit', 'TodoController@edit')->name('todo.edit');の記載があるので、TodoControllerクラスのeditメソッドに遷移する。
- editメソッドでは、$todoにTodoモデルをインスタンス化してfindメソッドで、主キーのidの変更前のレコードを取得する。
返り値は'todo.edit'に遷移しつつ、$todoの値をtodo.editの'todo'に渡すので、
- edit.phpに遷移するとformメソッドがあって、@csrfでトークンを発行してcsrf対策をしている。
また、inputタグのvalueのところに前のページから引き継いだ$todoのレコードからcontentカラムの値が選択されて表示されている。
ここで元の値を消して空の状態でsubmitすると、TodoRequest.phpのrulesに定義されている、'content' => 'required|max:255'の条件に引っかかってしまうので、エラーになり、messagesに定義されている、'content.required'のキーに対して'ToDoが入力されていません。'のバリューがグローバル変数の$errorに格納される。逆に、入力欄に256文字以上を入力すると、これもエラーになり、'content.max'のキーに対して'ToDoは :max 文字以内で入力してください。'のバリューがグローバル変数の$errorに格納される。
edit.phpに戻ると、@if($errors->has('content'))の記載があるので、先ほどのエラーが発生して、$errorsにエラー文が格納されていた場合、下の
{{ $errors->first('content') }}の部分でエラー文が表示される。
inputタグのvalueに新しく更新内容を入力して、submitボタンを押下すると、@method('PUT')の記述で、PUTメソッドで更新内容がPUTされる。
この時、記載が@method('PUT')なのは、HTML フォームの method 属性は put、patch、delete をサポートしていないため、フォームに隠しフィールドを追加して、疑似的に put、patch、delete のリクエストを表現しているためです。
- web.phpを確認すると、Route::put('/todo/{id}', 'TodoController@update')->name('todo.update');の記載があるので、putメソッドで、{id}を受け取りながら/todoに遷移。
- TodoControllerクラスのupdateメソッドを確認すると、引数でTodoRequestクラスをインスタンス化して$requestに格納しつつ、第二引数にルートパラメータが入っていて、$inputsにRequesクラスのインスタンス化のallメソッドで連想配列取得し、更にその連想配列から[content]を取得（＝新しく入れた値）
$todoにtodoモデルのインスタンス化からfindメソッドで（DB）から、idを取得する
該当id番号にfillメソッドで新規データを代入→saveメソッドでDBに保存（UPDATE todos SET content = '更新内容'のSQL文実行）
返り値はroute関数で'todo.show'にリダイレトしつつ、第2引数には、パスパラメータとして更新したToDoのIDを指定する
- web.phpを確認して、Route::get('/todo/{id}', 'TodoController@show')->name('todo.show');の記載があるので、idを保持しつつtodoに遷移し、TodoControllerクラスのshowメソッドを確認
- idを引数に持ちつつtodoモデルのインスタンス化からfindメソッドで取得する
返り値は'todo.show'に遷移しつつ$todoの値をtoodo.showのtodoに渡して、詳細画面に戻ってくる

- 削除処理の場合は、
削除するボタンを押下すると、deleteメソッドでroute関数で'todo.delete'に遷移しつつ、idをtodo.deleteの$todoに渡す
- web.phpを確認すると、Route::delete('/todo/{id}', 'TodoController@delete')->name('todo.delete');の記載があり、
todoにidを保持しつつ遷移し、TodoControllerクラスのdeleteメソッドに遷移
- $todo = todoモデルをインスタンス化してfindメソッドで引数のidのレコードを取得してきて、todoモデルのdelete()メソッドで、Todo.phpクラスにSoftDeletesトレイトを実装されているので、