<?php

namespace App\Controllers;

use App\Controllers\BaseController;
class ErrorController extends BaseController
{
  public function Forbidden()
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('login');
    } else {
      return $this->blade->render("errors.pages-503");
    }
  }

}
