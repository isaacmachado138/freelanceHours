<?php

namespace Database\Seeders;

use App\Models\User;
use App\Actions\ArrangePositions;
use App\Models\Project;
use App\Models\Proposal;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class DatabaseSeeder
 *
 * Esta classe é responsável por popular o banco de dados com dados iniciais
 * para os modelos `User`, `Project` e `Proposal`. Ela utiliza factories para
 * criar registros de usuários e para gerar projetos e propostas associadas a
 * usuários selecionados aleatoriamente.
 *
 * @package Database\Seeders
 */
class DatabaseSeeder extends Seeder
{

    /**
     * Executa o seed do banco de dados.
     *
     * Este método cria 200 usuários, seleciona 10 usuários aleatórios e para
     * cada um deles, cria um projeto. Para cada projeto, ele gera de 4 a 45
     * propostas. Em seguida, ele executa uma query para atualizar as posições
     * das propostas de acordo com o valor de horas (`hours`), atribuindo uma
     * nova posição a cada proposta.
     *
     * @return void
     */
    public function run(): void
    {
        // Cria 200 usuários
        User::factory()->count(200)->create();

        // Seleciona 10 usuários aleatórios
        User::query()->inRandomOrder()->limit(10)->get() ->each(function (User $u) {
            // Cria um projeto para cada usuário
            $project = Project::factory()->create(['created_by' => $u->id]);
            
            // Cria de 4 a 45 propostas para cada projeto
            Proposal::factory()->count(random_int(4, 45))->create(['project_id' => $project->id]);

            // Executa a query para ordenar as posições das propostas
            ArrangePositions::run($project->id);
        });
    }
}
