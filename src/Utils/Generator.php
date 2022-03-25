<?php
/*
 * Copyright 2020-2022 OSN Software Foundation, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */


namespace OSN\Invention\CLI\Utils;


use OSN\Framework\Console\App;

class Generator
{
    public function generate($path, $template, $call1, $call2, ...$args)
    {
        $template = $this->getTemplate($template);
        $content = sprintf($template, ...$args);

        if (file_exists($path)) {
            $call2("File exists");
            exit(-1);
        }

        file_put_contents($path, $content);
        $call1();
    }

    public function getTemplate($t)
    {
        $file = App::config('root_dir') . "/resources/templates/{$t}.template";
        return file_get_contents($file);
    }
}
