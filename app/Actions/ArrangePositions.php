<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;

/**
 * Class ArrangePositions
 *
 * Esta classe é responsável por reclassificar as propostas de um projeto
 * específico com base no valor de horas associadas a cada proposta.
 * O campo `position` de cada proposta é atualizado conforme a ordem crescente
 * de `hours`.
 *
 * @package App\Actions
 */
class ArrangePositions
{
    /**
     * Reclassifica as propostas de um projeto e atualiza suas posições.
     *
     * Este método realiza uma atualização nas propostas de um projeto específico,
     * organizando-as com base no valor de horas (`hours`). A classificação é feita
     * em ordem crescente, e o campo `position` de cada proposta é atualizado com a
     * nova ordem gerada.
     *
     * A query faz uso de uma subquery para calcular a posição (utilizando `row_number()`),
     * e um `JOIN` para aplicar essa posição às propostas.
     *
     * @param int $id O ID do projeto cujas propostas serão reclassificadas.
     * 
     * @return void
     */
    public static function run(int $id)
    {
        DB::statement('
        UPDATE proposals
        JOIN (
            SELECT id, row_number() OVER (ORDER BY hours ASC) as p
            FROM proposals
            WHERE project_id = ?
        ) as RankedProposals
        ON proposals.id = RankedProposals.id
        SET proposals.position = RankedProposals.p
        WHERE proposals.project_id = ?;
        ', [$id, $id]); 
    }
}
