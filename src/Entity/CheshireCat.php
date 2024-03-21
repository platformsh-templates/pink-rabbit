<?php

namespace App\Entity;


class CheshireCat
{
    public function followMe(): string
    {
        /**
         * Call-graphs come in multiple flavours for multiple dimensions.
         * Have you seen them all?
         *
         * This call is clearly visible on the CPU one.
         * Click on the "Processor" icon on the top bar
         * https://docs.blackfire.io/profiling-cookbooks/understanding-call-graphs
         *
         * Now, where should we go next?
         */
        return password_hash('/sighting/666', PASSWORD_BCRYPT, ['cost' => 13]);
    }
}
