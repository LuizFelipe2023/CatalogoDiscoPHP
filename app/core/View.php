<?php

class View
{
    public static function render(string $view, array $data = [])
    {
        extract($data);

        ob_start();

        $viewPath = __DIR__ . "/../views/$view.php";
        
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo "Erro: View [$view] não encontrada.";
        }

        $content = ob_get_clean();

        require __DIR__ . "/../views/layouts/main.php";
    }
}

?>