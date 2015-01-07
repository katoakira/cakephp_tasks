<?php 
    class TasksController extends AppController {
        //動作確認のためscaffoldを使う
        public $scaffold;

        public function index() {
            
            //　空のデータをビューへ渡す
            //  $tasks_data = array();
            //  $this->set('tasks_data', $tasks_data);
              
            // データをモデルから取得してビューへ渡す
            $options = array(
                'conditions' => array(
                    'Task.status' => 0
                )
            );
            $tasks_data = $this->Task->find('all', $options);
            $this->set('tasks_data', $tasks_data);

            // app/View/Tasks/index.ctpを表示
            $this->render('index');
        }
        
        public function done() {
            // URLの末尾からタスクのIDを取得してメッセージを作成
            //$msg = sprintf(
            //    'タスク %s を完了しました。',
            //    $this->request->pass[0]
            // );
            
            // URLの末尾からタスクのIDを取得してデータを更新
            $id = $this->request->pass[0];
            $this->Task->id = $id;
            $this->Task->saveField('status', 1);
            $msg = sprintf('タスク %s を完了しました。', $id);

            // メッセージを表示してリダイレクト
            $this->Session->setFlash($msg);
            $this->redirect('/Tasks/index');
        }

        public function create() {
            
            // POSTされた場合だけ処理を行う
            if ($this->request->is('post')) {
                $data = array(
                    'name' => $this->request->data['name'],
                    // bodyを追加
                    'body' => $this->request->data['body']
                );
  
                // データを登録
                $id = $this->Task->save($data);   
                if ($id === false) { // ifブロックを追加
                    $this->render('create');
                    return;
                }             
                $msg = sprintf(
                    'タスク %s を登録しました。',
                    $this->Task->id
                );

                // メッセージを表示してリダイレクト
                $this->Session->setFlash($msg);
                $this->redirect('/Tasks/index');
                return;
            }
            $this->render('create');
        }

        public function edit() {
            // 指定されたタスクのデータを取得
            $id = $this->request->pass[0];
            $options = array(
                'condition' => array(
                    'Task.id' => $id,
                    'Task.status' => 0
                )
            );
            $task = $this->Task->find('first', $options);

            // データが見つからない場合は一覧へ
            if ($task == false) {
                $this->Session->setFlash('タスクが見つかりません');
                $this->redirect('/Tasks/index');
            }

            // フォームが送信された場合は更新にトライ
            if ($this->request->is('post')) {
                $data = array(
                    'id' => $id,
                    'name' => $this->request->data['Task']['name'],
                    'body' => $this->request->data['Task']['body']
                );
                if ($this->Task->save($data)) {
                    $this->Session->setFlash('更新しました');
                    $this->redirect('/Task/index');
                }
            } else {
                // POSTされていない場合は初期データをフォームにセット
                $this->request->data = $task;
            } 
        }
    }
