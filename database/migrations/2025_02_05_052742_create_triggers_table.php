<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
  public function up(): void
  {
    // ================================
    // TRIGGERS PARA LA TABLA roles
    // ================================
    DB::unprepared("
      CREATE TRIGGER trg_roles_insert
      AFTER INSERT ON roles
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (roles_id, operation, new_data)
        VALUES (
          NEW.id,
          'INSERT',
          CONCAT(
            'name=', NEW.name,
            ',enabled=', NEW.enabled,
            ',created_at=', NEW.created_at,
            ',updated_at=', NEW.updated_at
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_roles_update
      AFTER UPDATE ON roles
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (roles_id, operation, old_data, new_data)
        VALUES (
          NEW.id,
          'UPDATE',
          CONCAT(
            'name=', OLD.name,
            ',enabled=', OLD.enabled,
            ',created_at=', OLD.created_at,
            ',updated_at=', OLD.updated_at
          ),
          CONCAT(
            'name=', NEW.name,
            ',enabled=', NEW.enabled,
            ',created_at=', NEW.created_at,
            ',updated_at=', NEW.updated_at
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_roles_delete
      AFTER UPDATE ON roles
      FOR EACH ROW
      BEGIN
        IF NEW.enabled = 0 AND OLD.enabled <> 0 THEN
          INSERT INTO logs (roles_id, operation, old_data, new_data)
          VALUES (
            NEW.id,
            'DELETE',
            CONCAT(
              'name=', OLD.name,
              ',enabled=', OLD.enabled,
              ',created_at=', OLD.created_at,
              ',updated_at=', OLD.updated_at
            ),
            'enabled=0'
          );
        END IF;
      END;
    ");

    // ================================
    // TRIGGERS PARA LA TABLA colegios
    // ================================
    DB::unprepared("
      CREATE TRIGGER trg_colegios_insert
      AFTER INSERT ON colegios
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (colegios_id, operation, new_data)
        VALUES (
          NEW.id,
          'INSERT',
          CONCAT(
            'name=', NEW.name,
            ',address=', IFNULL(NEW.address, ''),
            ',enabled=', NEW.enabled,
            ',created_at=', NEW.created_at,
            ',updated_at=', NEW.updated_at
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_colegios_update
      AFTER UPDATE ON colegios
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (colegios_id, operation, old_data, new_data)
        VALUES (
          NEW.id,
          'UPDATE',
          CONCAT(
            'name=', OLD.name,
            ',address=', IFNULL(OLD.address, ''),
            ',enabled=', OLD.enabled,
            ',created_at=', OLD.created_at,
            ',updated_at=', OLD.updated_at
          ),
          CONCAT(
            'name=', NEW.name,
            ',address=', IFNULL(NEW.address, ''),
            ',enabled=', NEW.enabled,
            ',created_at=', NEW.created_at,
            ',updated_at=', NEW.updated_at
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_colegios_delete
      AFTER UPDATE ON colegios
      FOR EACH ROW
      BEGIN
        IF NEW.enabled = 0 AND OLD.enabled <> 0 THEN
          INSERT INTO logs (colegios_id, operation, old_data, new_data)
          VALUES (
            NEW.id,
            'DELETE',
            CONCAT(
              'name=', OLD.name,
              ',address=', IFNULL(OLD.address, ''),
              ',enabled=', OLD.enabled,
              ',created_at=', OLD.created_at,
              ',updated_at=', OLD.updated_at
            ),
            'enabled=0'
          );
        END IF;
      END;
    ");

    // ================================
    // TRIGGERS PARA LA TABLA users
    // ================================
    DB::unprepared("
      CREATE TRIGGER trg_users_insert
      AFTER INSERT ON users
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (users_id, operation, new_data)
        VALUES (
          NEW.id,
          'INSERT',
          CONCAT(
            'name=', NEW.name,
            ',email=', NEW.email,
            ',role_id=', NEW.role_id,
            ',colegio_id=', IFNULL(NEW.colegio_id, 'NULL'),
            ',enabled=', NEW.enabled,
            ',nivel=', IFNULL(NEW.nivel, ''),
            ',phone=', IFNULL(NEW.phone, ''),
            ',date_of_birth=', IFNULL(NEW.date_of_birth, ''),
            ',grado=', IFNULL(NEW.grado, ''),
            ',created_at=', NEW.created_at,
            ',updated_at=', NEW.updated_at
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_users_update
      AFTER UPDATE ON users
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (users_id, operation, old_data, new_data)
        VALUES (
          NEW.id,
          'UPDATE',
          CONCAT(
            'name=', OLD.name,
            ',email=', OLD.email,
            ',role_id=', OLD.role_id,
            ',colegio_id=', IFNULL(OLD.colegio_id, 'NULL'),
            ',enabled=', OLD.enabled,
            ',nivel=', IFNULL(OLD.nivel, ''),
            ',phone=', IFNULL(OLD.phone, ''),
            ',date_of_birth=', IFNULL(OLD.date_of_birth, ''),
            ',grado=', IFNULL(OLD.grado, ''),
            ',created_at=', OLD.created_at,
            ',updated_at=', OLD.updated_at
          ),
          CONCAT(
            'name=', NEW.name,
            ',email=', NEW.email,
            ',role_id=', NEW.role_id,
            ',colegio_id=', IFNULL(NEW.colegio_id, 'NULL'),
            ',enabled=', NEW.enabled,
            ',nivel=', IFNULL(NEW.nivel, ''),
            ',phone=', IFNULL(NEW.phone, ''),
            ',date_of_birth=', IFNULL(NEW.date_of_birth, ''),
            ',grado=', IFNULL(NEW.grado, ''),
            ',created_at=', NEW.created_at,
            ',updated_at=', NEW.updated_at
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_users_delete
      AFTER UPDATE ON users
      FOR EACH ROW
      BEGIN
        IF NEW.enabled = 0 AND OLD.enabled <> 0 THEN
          INSERT INTO logs (users_id, operation, old_data, new_data)
          VALUES (
            NEW.id,
            'DELETE',
            CONCAT(
              'name=', OLD.name,
              ',email=', OLD.email,
              ',role_id=', OLD.role_id,
              ',colegio_id=', IFNULL(OLD.colegio_id, 'NULL'),
              ',enabled=', OLD.enabled,
              ',nivel=', IFNULL(OLD.nivel, ''),
              ',phone=', IFNULL(OLD.phone, ''),
              ',date_of_birth=', IFNULL(OLD.date_of_birth, ''),
              ',grado=', IFNULL(OLD.grado, ''),
              ',created_at=', OLD.created_at,
              ',updated_at=', OLD.updated_at
            ),
            'enabled=0'
          );
        END IF;
      END;
    ");

    // ================================
    // TRIGGERS PARA LA TABLA cursos
    // ================================
    DB::unprepared("
      CREATE TRIGGER trg_cursos_insert
      AFTER INSERT ON cursos
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (cursos_id, operation, new_data)
        VALUES (
          NEW.id,
          'INSERT',
          CONCAT(
            'name=', NEW.name,
            ',description=', NEW.description,
            ',professor_id=', NEW.professor_id,
            ',enabled=', NEW.enabled,
            ',created_at=', NEW.created_at,
            ',updated_at=', NEW.updated_at
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_cursos_update
      AFTER UPDATE ON cursos
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (cursos_id, operation, old_data, new_data)
        VALUES (
          NEW.id,
          'UPDATE',
          CONCAT(
            'name=', OLD.name,
            ',description=', OLD.description,
            ',professor_id=', OLD.professor_id,
            ',enabled=', OLD.enabled,
            ',created_at=', OLD.created_at,
            ',updated_at=', OLD.updated_at
          ),
          CONCAT(
            'name=', NEW.name,
            ',description=', NEW.description,
            ',professor_id=', NEW.professor_id,
            ',enabled=', NEW.enabled,
            ',created_at=', NEW.created_at,
            ',updated_at=', NEW.updated_at
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_cursos_delete
      AFTER UPDATE ON cursos
      FOR EACH ROW
      BEGIN
        IF NEW.enabled = 0 AND OLD.enabled <> 0 THEN
          INSERT INTO logs (cursos_id, operation, old_data, new_data)
          VALUES (
            NEW.id,
            'DELETE',
            CONCAT(
              'name=', OLD.name,
              ',description=', OLD.description,
              ',professor_id=', OLD.professor_id,
              ',enabled=', OLD.enabled,
              ',created_at=', OLD.created_at,
              ',updated_at=', OLD.updated_at
            ),
            'enabled=0'
          );
        END IF;
      END;
    ");

    // ================================
    // TRIGGERS PARA LA TABLA curso_usuarios
    // ================================
    DB::unprepared("
      CREATE TRIGGER trg_curso_usuarios_insert
      AFTER INSERT ON curso_usuarios
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (curso_usuarios_id, operation, new_data)
        VALUES (
          NEW.id,
          'INSERT',
          CONCAT(
            'course_id=', NEW.course_id,
            ',user_id=', NEW.user_id,
            ',enabled=', NEW.enabled,
            ',created_at=', NEW.created_at,
            ',updated_at=', NEW.updated_at
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_curso_usuarios_update
      AFTER UPDATE ON curso_usuarios
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (curso_usuarios_id, operation, old_data, new_data)
        VALUES (
          NEW.id,
          'UPDATE',
          CONCAT(
            'course_id=', OLD.course_id,
            ',user_id=', OLD.user_id,
            ',enabled=', OLD.enabled,
            ',created_at=', OLD.created_at,
            ',updated_at=', OLD.updated_at
          ),
          CONCAT(
            'course_id=', NEW.course_id,
            ',user_id=', NEW.user_id,
            ',enabled=', NEW.enabled,
            ',created_at=', NEW.created_at,
            ',updated_at=', NEW.updated_at
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_curso_usuarios_delete
      AFTER UPDATE ON curso_usuarios
      FOR EACH ROW
      BEGIN
        IF NEW.enabled = 0 AND OLD.enabled <> 0 THEN
          INSERT INTO logs (curso_usuarios_id, operation, old_data, new_data)
          VALUES (
            NEW.id,
            'DELETE',
            CONCAT(
              'course_id=', OLD.course_id,
              ',user_id=', OLD.user_id,
              ',enabled=', OLD.enabled,
              ',created_at=', OLD.created_at,
              ',updated_at=', OLD.updated_at
            ),
            'enabled=0'
          );
        END IF;
      END;
    ");

    // ================================
    // TRIGGERS PARA LA TABLA temas
    // ================================
    DB::unprepared("
      CREATE TRIGGER trg_temas_insert
      AFTER INSERT ON temas
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (temas_id, operation, new_data)
        VALUES (
          NEW.id,
          'INSERT',
          CONCAT(
            'curso_id=', NEW.curso_id,
            ',title=', NEW.title,
            ',description=', IFNULL(NEW.description, ''),
            ',enabled=', NEW.enabled,
            ',created_at=', NEW.created_at,
            ',updated_at=', NEW.updated_at
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_temas_update
      AFTER UPDATE ON temas
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (temas_id, operation, old_data, new_data)
        VALUES (
          NEW.id,
          'UPDATE',
          CONCAT(
            'curso_id=', OLD.curso_id,
            ',title=', OLD.title,
            ',description=', IFNULL(OLD.description, ''),
            ',enabled=', OLD.enabled,
            ',created_at=', OLD.created_at,
            ',updated_at=', OLD.updated_at
          ),
          CONCAT(
            'curso_id=', NEW.curso_id,
            ',title=', NEW.title,
            ',description=', IFNULL(NEW.description, ''),
            ',enabled=', NEW.enabled,
            ',created_at=', NEW.created_at,
            ',updated_at=', NEW.updated_at
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_temas_delete
      AFTER UPDATE ON temas
      FOR EACH ROW
      BEGIN
        IF NEW.enabled = 0 AND OLD.enabled <> 0 THEN
          INSERT INTO logs (temas_id, operation, old_data, new_data)
          VALUES (
            NEW.id,
            'DELETE',
            CONCAT(
              'curso_id=', OLD.curso_id,
              ',title=', OLD.title,
              ',description=', IFNULL(OLD.description, ''),
              ',enabled=', OLD.enabled,
              ',created_at=', OLD.created_at,
              ',updated_at=', OLD.updated_at
            ),
            'enabled=0'
          );
        END IF;
      END;
    ");

    // ================================
    // TRIGGERS PARA LA TABLA lecciones
    // ================================
    DB::unprepared("
      CREATE TRIGGER trg_lecciones_insert
      AFTER INSERT ON lecciones
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (lecciones_id, operation, new_data)
        VALUES (
          NEW.id,
          'INSERT',
          CONCAT(
            'tema_id=', NEW.tema_id,
            ',title=', NEW.title,
            ',description=', IFNULL(NEW.description, ''),
            ',difficulty=', NEW.difficulty,
            ',created_by=', NEW.created_by,
            ',enabled=', NEW.enabled,
            ',created_at=', NEW.created_at,
            ',updated_at=', NEW.updated_at
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_lecciones_update
      AFTER UPDATE ON lecciones
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (lecciones_id, operation, old_data, new_data)
        VALUES (
          NEW.id,
          'UPDATE',
          CONCAT(
            'tema_id=', OLD.tema_id,
            ',title=', OLD.title,
            ',description=', IFNULL(OLD.description, ''),
            ',difficulty=', OLD.difficulty,
            ',created_by=', OLD.created_by,
            ',enabled=', OLD.enabled,
            ',created_at=', OLD.created_at,
            ',updated_at=', OLD.updated_at
          ),
          CONCAT(
            'tema_id=', NEW.tema_id,
            ',title=', NEW.title,
            ',description=', IFNULL(NEW.description, ''),
            ',difficulty=', NEW.difficulty,
            ',created_by=', NEW.created_by,
            ',enabled=', NEW.enabled,
            ',created_at=', NEW.created_at,
            ',updated_at=', NEW.updated_at
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_lecciones_delete
      AFTER UPDATE ON lecciones
      FOR EACH ROW
      BEGIN
        IF NEW.enabled = 0 AND OLD.enabled <> 0 THEN
          INSERT INTO logs (lecciones_id, operation, old_data, new_data)
          VALUES (
            NEW.id,
            'DELETE',
            CONCAT(
              'tema_id=', OLD.tema_id,
              ',title=', OLD.title,
              ',description=', IFNULL(OLD.description, ''),
              ',difficulty=', OLD.difficulty,
              ',created_by=', OLD.created_by,
              ',enabled=', OLD.enabled,
              ',created_at=', OLD.created_at,
              ',updated_at=', OLD.updated_at
            ),
            'enabled=0'
          );
        END IF;
      END;
    ");

    // ================================
    // TRIGGERS PARA LA TABLA actividades
    // ================================
    DB::unprepared("
      CREATE TRIGGER trg_actividades_insert
      AFTER INSERT ON actividades
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (actividades_id, operation, new_data)
        VALUES (
          NEW.id,
          'INSERT',
          CONCAT(
            'leccion_id=', NEW.leccion_id,
            ',name=', NEW.name,
            ',content=', NEW.content,
            ',enabled=', NEW.enabled,
            ',created_at=', NEW.created_at,
            ',updated_at=', NEW.updated_at
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_actividades_update
      AFTER UPDATE ON actividades
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (actividades_id, operation, old_data, new_data)
        VALUES (
          NEW.id,
          'UPDATE',
          CONCAT(
            'leccion_id=', OLD.leccion_id,
            ',name=', OLD.name,
            ',content=', OLD.content,
            ',enabled=', OLD.enabled,
            ',created_at=', OLD.created_at,
            ',updated_at=', OLD.updated_at
          ),
          CONCAT(
            'leccion_id=', NEW.leccion_id,
            ',name=', NEW.name,
            ',content=', NEW.content,
            ',enabled=', NEW.enabled,
            ',created_at=', NEW.created_at,
            ',updated_at=', NEW.updated_at
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_actividades_delete
      AFTER UPDATE ON actividades
      FOR EACH ROW
      BEGIN
        IF NEW.enabled = 0 AND OLD.enabled <> 0 THEN
          INSERT INTO logs (actividades_id, operation, old_data, new_data)
          VALUES (
            NEW.id,
            'DELETE',
            CONCAT(
              'leccion_id=', OLD.leccion_id,
              ',name=', OLD.name,
              ',content=', OLD.content,
              ',enabled=', OLD.enabled,
              ',created_at=', OLD.created_at,
              ',updated_at=', OLD.updated_at
            ),
            'enabled=0'
          );
        END IF;
      END;
    ");

    // ================================
    // TRIGGERS PARA LA TABLA usuario_lecciones
    // ================================
    DB::unprepared("
      CREATE TRIGGER trg_usuario_lecciones_insert
      AFTER INSERT ON usuario_lecciones
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (usuario_lecciones_id, operation, new_data)
        VALUES (
          NEW.id,
          'INSERT',
          CONCAT(
            'user_id=', NEW.user_id,
            ',leccion_id=', NEW.leccion_id,
            ',status=', NEW.status,
            ',score=', NEW.score,
            ',attempts=', NEW.attempts,
            ',enabled=', NEW.enabled,
            ',started_at=', IFNULL(NEW.started_at, ''),
            ',completed_at=', IFNULL(NEW.completed_at, ''),
            ',created_at=', NEW.created_at,
            ',updated_at=', NEW.updated_at
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_usuario_lecciones_update
      AFTER UPDATE ON usuario_lecciones
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (usuario_lecciones_id, operation, old_data, new_data)
        VALUES (
          NEW.id,
          'UPDATE',
          CONCAT(
            'user_id=', OLD.user_id,
            ',leccion_id=', OLD.leccion_id,
            ',status=', OLD.status,
            ',score=', OLD.score,
            ',attempts=', OLD.attempts,
            ',enabled=', OLD.enabled,
            ',started_at=', IFNULL(OLD.started_at, ''),
            ',completed_at=', IFNULL(OLD.completed_at, ''),
            ',created_at=', OLD.created_at,
            ',updated_at=', OLD.updated_at
          ),
          CONCAT(
            'user_id=', NEW.user_id,
            ',leccion_id=', NEW.leccion_id,
            ',status=', NEW.status,
            ',score=', NEW.score,
            ',attempts=', NEW.attempts,
            ',enabled=', NEW.enabled,
            ',started_at=', IFNULL(NEW.started_at, ''),
            ',completed_at=', IFNULL(NEW.completed_at, ''),
            ',created_at=', NEW.created_at,
            ',updated_at=', NEW.updated_at
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_usuario_lecciones_delete
      AFTER UPDATE ON usuario_lecciones
      FOR EACH ROW
      BEGIN
        IF NEW.enabled = 0 AND OLD.enabled <> 0 THEN
          INSERT INTO logs (usuario_lecciones_id, operation, old_data, new_data)
          VALUES (
            NEW.id,
            'DELETE',
            CONCAT(
              'user_id=', OLD.user_id,
              ',leccion_id=', OLD.leccion_id,
              ',status=', OLD.status,
              ',score=', OLD.score,
              ',attempts=', OLD.attempts,
              ',enabled=', OLD.enabled,
              ',started_at=', IFNULL(OLD.started_at, ''),
              ',completed_at=', IFNULL(OLD.completed_at, ''),
              ',created_at=', OLD.created_at,
              ',updated_at=', OLD.updated_at
            ),
            'enabled=0'
          );
        END IF;
      END;
    ");

    // ================================
    // TRIGGERS PARA LA TABLA evaluaciones_alternativas
    // ================================
    DB::unprepared("
      CREATE TRIGGER trg_evaluaciones_alternativas_insert
      AFTER INSERT ON evaluaciones_alternativas
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (evaluaciones_alternativas_id, operation, new_data)
        VALUES (
          NEW.id,
          'INSERT',
          CONCAT(
            'user_id=', NEW.user_id,
            ',leccion_id=', NEW.leccion_id,
            ',actividad_id=', NEW.actividad_id,
            ',tipo_evaluacion=', NEW.tipo_evaluacion,
            ',resultado=', NEW.resultado,
            ',created_at=', NEW.created_at,
            ',updated_at=', NEW.updated_at
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_evaluaciones_alternativas_update
      AFTER UPDATE ON evaluaciones_alternativas
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (evaluaciones_alternativas_id, operation, old_data, new_data)
        VALUES (
          NEW.id,
          'UPDATE',
          CONCAT(
            'user_id=', OLD.user_id,
            ',leccion_id=', OLD.leccion_id,
            ',actividad_id=', OLD.actividad_id,
            ',tipo_evaluacion=', OLD.tipo_evaluacion,
            ',resultado=', OLD.resultado,
            ',created_at=', OLD.created_at,
            ',updated_at=', OLD.updated_at
          ),
          CONCAT(
            'user_id=', NEW.user_id,
            ',leccion_id=', NEW.leccion_id,
            ',actividad_id=', NEW.actividad_id,
            ',tipo_evaluacion=', NEW.tipo_evaluacion,
            ',resultado=', NEW.resultado,
            ',created_at=', NEW.created_at,
            ',updated_at=', NEW.updated_at
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_evaluaciones_alternativas_delete
      AFTER UPDATE ON evaluaciones_alternativas
      FOR EACH ROW
      BEGIN
        IF NEW.enabled = 0 AND OLD.enabled <> 0 THEN
          INSERT INTO logs (evaluaciones_alternativas_id, operation, old_data, new_data)
          VALUES (
            NEW.id,
            'DELETE',
            CONCAT(
              'user_id=', OLD.user_id,
              ',leccion_id=', OLD.leccion_id,
              ',actividad_id=', OLD.actividad_id,
              ',tipo_evaluacion=', OLD.tipo_evaluacion,
              ',resultado=', OLD.resultado,
              ',created_at=', OLD.created_at,
              ',updated_at=', OLD.updated_at
            ),
            'enabled=0'
          );
        END IF;
      END;
    ");
    
    // ================================
    // TRIGGERS PARA LA TABLA rachas_amigos
    // ================================
    DB::unprepared("
      CREATE TRIGGER trg_rachas_amigos_insert
      AFTER INSERT ON rachas_amigos
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (rachas_amigos_id, operation, new_data)
        VALUES (
          NEW.id,
          'INSERT',
          CONCAT(
            'user1_id=', NEW.user1_id,
            ',user2_id=', NEW.user2_id,
            ',streak_days=', NEW.streak_days,
            ',last_challenge_at=', IFNULL(NEW.last_challenge_at, ''),
            ',created_at=', NEW.created_at,
            ',updated_at=', NEW.updated_at
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_rachas_amigos_update
      AFTER UPDATE ON rachas_amigos
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (rachas_amigos_id, operation, old_data, new_data)
        VALUES (
          NEW.id,
          'UPDATE',
          CONCAT(
            'user1_id=', OLD.user1_id,
            ',user2_id=', OLD.user2_id,
            ',streak_days=', OLD.streak_days,
            ',last_challenge_at=', IFNULL(OLD.last_challenge_at, ''),
            ',created_at=', OLD.created_at,
            ',updated_at=', OLD.updated_at
          ),
          CONCAT(
            'user1_id=', NEW.user1_id,
            ',user2_id=', NEW.user2_id,
            ',streak_days=', NEW.streak_days,
            ',last_challenge_at=', IFNULL(NEW.last_challenge_at, ''),
            ',created_at=', NEW.created_at,
            ',updated_at=', NEW.updated_at
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_rachas_amigos_delete
      AFTER UPDATE ON rachas_amigos
      FOR EACH ROW
      BEGIN
        IF NEW.enabled = 0 AND OLD.enabled <> 0 THEN
          INSERT INTO logs (rachas_amigos_id, operation, old_data, new_data)
          VALUES (
            NEW.id,
            'DELETE',
            CONCAT(
              'user1_id=', OLD.user1_id,
              ',user2_id=', OLD.user2_id,
              ',streak_days=', OLD.streak_days,
              ',last_challenge_at=', IFNULL(OLD.last_challenge_at, ''),
              ',created_at=', OLD.created_at,
              ',updated_at=', OLD.updated_at
            ),
            'enabled=0'
          );
        END IF;
      END;
    ");

    // ================================
    // TRIGGERS PARA LA TABLA puntos_usuario
    // ================================
    DB::unprepared("
      CREATE TRIGGER trg_puntos_usuario_insert
      AFTER INSERT ON puntos_usuario
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (puntos_usuario_id, operation, new_data)
        VALUES (
          NEW.id,
          'INSERT',
          CONCAT(
            'user_id=', NEW.user_id,
            ',puntos=', NEW.puntos,
            ',motivo=', NEW.motivo,
            ',created_at=', NEW.created_at,
            ',updated_at=', NEW.updated_at
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_puntos_usuario_update
      AFTER UPDATE ON puntos_usuario
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (puntos_usuario_id, operation, old_data, new_data)
        VALUES (
          NEW.id,
          'UPDATE',
          CONCAT(
            'user_id=', OLD.user_id,
            ',puntos=', OLD.puntos,
            ',motivo=', OLD.motivo,
            ',created_at=', OLD.created_at,
            ',updated_at=', OLD.updated_at
          ),
          CONCAT(
            'user_id=', NEW.user_id,
            ',puntos=', NEW.puntos,
            ',motivo=', NEW.motivo,
            ',created_at=', NEW.created_at,
            ',updated_at=', NEW.updated_at
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_puntos_usuario_delete
      AFTER DELETE ON puntos_usuario
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (puntos_usuario_id, operation, old_data)
        VALUES (
          OLD.id,
          'DELETE',
          CONCAT(
            'user_id=', OLD.user_id,
            ',puntos=', OLD.puntos,
            ',motivo=', OLD.motivo,
            ',created_at=', OLD.created_at,
            ',updated_at=', OLD.updated_at
          )
        );
      END;
    ");

    // ================================
    // TRIGGERS PARA LA TABLA recompensas
    // ================================
    DB::unprepared("
      CREATE TRIGGER trg_recompensas_insert
      AFTER INSERT ON recompensas
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (recompensas_id, operation, new_data)
        VALUES (
          NEW.id,
          'INSERT',
          CONCAT(
            'nombre=', NEW.nombre,
            ',descripcion=', NEW.descripcion,
            ',puntos_requeridos=', NEW.puntos_requeridos,
            ',estado=', NEW.estado,
            ',created_at=', NEW.created_at,
            ',updated_at=', NEW.updated_at
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_recompensas_update
      AFTER UPDATE ON recompensas
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (recompensas_id, operation, old_data, new_data)
        VALUES (
          NEW.id,
          'UPDATE',
          CONCAT(
            'nombre=', OLD.nombre,
            ',descripcion=', OLD.descripcion,
            ',puntos_requeridos=', OLD.puntos_requeridos,
            ',estado=', OLD.estado,
            ',created_at=', OLD.created_at,
            ',updated_at=', OLD.updated_at
          ),
          CONCAT(
            'nombre=', NEW.nombre,
            ',descripcion=', NEW.descripcion,
            ',puntos_requeridos=', NEW.puntos_requeridos,
            ',estado=', NEW.estado,
            ',created_at=', NEW.created_at,
            ',updated_at=', NEW.updated_at
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_recompensas_delete
      AFTER UPDATE ON recompensas
      FOR EACH ROW
      BEGIN
        IF NEW.estado = 0 AND OLD.estado <> 0 THEN
          INSERT INTO logs (recompensas_id, operation, old_data, new_data)
          VALUES (
            NEW.id,
            'DELETE',
            CONCAT(
              'nombre=', OLD.nombre,
              ',descripcion=', OLD.descripcion,
              ',puntos_requeridos=', OLD.puntos_requeridos,
              ',estado=', OLD.estado,
              ',created_at=', OLD.created_at,
              ',updated_at=', OLD.updated_at
            ),
            'estado=0'
          );
        END IF;
      END;
    ");

    // ================================
    // TRIGGERS PARA LA TABLA notificaciones
    // ================================
    DB::unprepared("
      CREATE TRIGGER trg_notificaciones_insert
      AFTER INSERT ON notificaciones
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (notificaciones_id, operation, new_data)
        VALUES (
          NEW.id,
          'INSERT',
          CONCAT(
            'user_id=', NEW.user_id,
            ',tipo=', NEW.tipo,
            ',mensaje=', NEW.mensaje,
            ',estado=', NEW.estado,
            ',fecha_envio=', NEW.fecha_envio
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_notificaciones_update
      AFTER UPDATE ON notificaciones
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (notificaciones_id, operation, old_data, new_data)
        VALUES (
          NEW.id,
          'UPDATE',
          CONCAT(
            'user_id=', OLD.user_id,
            ',tipo=', OLD.tipo,
            ',mensaje=', OLD.mensaje,
            ',estado=', OLD.estado,
            ',fecha_envio=', OLD.fecha_envio
          ),
          CONCAT(
            'user_id=', NEW.user_id,
            ',tipo=', NEW.tipo,
            ',mensaje=', NEW.mensaje,
            ',estado=', NEW.estado,
            ',fecha_envio=', NEW.fecha_envio
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_notificaciones_delete
      AFTER DELETE ON notificaciones
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (notificaciones_id, operation, old_data)
        VALUES (
          OLD.id,
          'DELETE',
          CONCAT(
            'user_id=', OLD.user_id,
            ',tipo=', OLD.tipo,
            ',mensaje=', OLD.mensaje,
            ',estado=', OLD.estado,
            ',fecha_envio=', OLD.fecha_envio
          )
        );
      END;
    ");

    // ================================
    // TRIGGERS PARA LA TABLA usuario_recompensas -> ahora "usuario_recompensas"
    // ================================
    DB::unprepared("
      CREATE TRIGGER trg_usuario_recompensas_insert
      AFTER INSERT ON usuario_recompensas
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (user_recompensas_id, operation, new_data)
        VALUES (
          NEW.id,
          'INSERT',
          CONCAT(
            'user_id=', NEW.user_id,
            ',recompensa_id=', NEW.recompensa_id,
            ',awarded_at=', NEW.awarded_at,
            ',created_at=', NEW.created_at,
            ',updated_at=', NEW.updated_at
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_usuario_recompensas_update
      AFTER UPDATE ON usuario_recompensas
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (user_recompensas_id, operation, old_data, new_data)
        VALUES (
          NEW.id,
          'UPDATE',
          CONCAT(
            'user_id=', OLD.user_id,
            ',recompensa_id=', OLD.recompensa_id,
            ',awarded_at=', OLD.awarded_at,
            ',created_at=', OLD.created_at,
            ',updated_at=', OLD.updated_at
          ),
          CONCAT(
            'user_id=', NEW.user_id,
            ',recompensa_id=', NEW.recompensa_id,
            ',awarded_at=', NEW.awarded_at,
            ',created_at=', NEW.created_at,
            ',updated_at=', NEW.updated_at
          )
        );
      END;
    ");
    DB::unprepared("
      CREATE TRIGGER trg_usuario_recompensas_delete
      AFTER DELETE ON usuario_recompensas
      FOR EACH ROW
      BEGIN
        INSERT INTO logs (user_recompensas_id, operation, old_data)
        VALUES (
          OLD.id,
          'DELETE',
          CONCAT(
            'user_id=', OLD.user_id,
            ',recompensa_id=', OLD.recompensa_id,
            ',awarded_at=', OLD.awarded_at,
            ',created_at=', OLD.created_at,
            ',updated_at=', OLD.updated_at
          )
        );
      END;
    ");
  }

  public function down(): void
  {
    // Eliminar triggers en orden inverso
    DB::unprepared("DROP TRIGGER IF EXISTS trg_roles_insert");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_roles_update");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_roles_delete");

    DB::unprepared("DROP TRIGGER IF EXISTS trg_colegios_insert");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_colegios_update");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_colegios_delete");

    DB::unprepared("DROP TRIGGER IF EXISTS trg_users_insert");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_users_update");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_users_delete");

    DB::unprepared("DROP TRIGGER IF EXISTS trg_cursos_insert");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_cursos_update");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_cursos_delete");

    DB::unprepared("DROP TRIGGER IF EXISTS trg_curso_usuarios_insert");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_curso_usuarios_update");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_curso_usuarios_delete");

    DB::unprepared("DROP TRIGGER IF EXISTS trg_temas_insert");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_temas_update");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_temas_delete");

    DB::unprepared("DROP TRIGGER IF EXISTS trg_lecciones_insert");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_lecciones_update");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_lecciones_delete");

    DB::unprepared("DROP TRIGGER IF EXISTS trg_actividades_insert");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_actividades_update");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_actividades_delete");

    DB::unprepared("DROP TRIGGER IF EXISTS trg_usuario_lecciones_insert");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_usuario_lecciones_update");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_usuario_lecciones_delete");

    DB::unprepared("DROP TRIGGER IF EXISTS trg_evaluaciones_alternativas_insert");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_evaluaciones_alternativas_update");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_evaluaciones_alternativas_delete");

    DB::unprepared("DROP TRIGGER IF EXISTS trg_rachas_amigos_insert");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_rachas_amigos_update");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_rachas_amigos_delete");

    DB::unprepared("DROP TRIGGER IF EXISTS trg_puntos_usuario_insert");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_puntos_usuario_update");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_puntos_usuario_delete");

    DB::unprepared("DROP TRIGGER IF EXISTS trg_recompensas_insert");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_recompensas_update");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_recompensas_delete");

    DB::unprepared("DROP TRIGGER IF EXISTS trg_usuario_recompensas_insert");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_usuario_recompensas_update");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_usuario_recompensas_delete");

    DB::unprepared("DROP TRIGGER IF EXISTS trg_notificaciones_insert");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_notificaciones_update");
    DB::unprepared("DROP TRIGGER IF EXISTS trg_notificaciones_delete");
  }
};
