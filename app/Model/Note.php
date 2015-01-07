<?php 

    class Note extends AppModel {
       
        public $belongsTo = array('Task');

        public $validate = array(
            'body' => array(
                'rule' => array('maxLength', 255),
                'required' => true,
                'allowEmpty' => false,
                'message' => '入力してください'
            )
        );
    }
