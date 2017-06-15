<?php

class Test extends UltraController{
    public function execute() {
        parent::execute();
        UltraPHP::view('test');
    }
}
