# GOBIERNO DE EL SALVADOR
## PRUEBA PRÁCTICA PRODUCT OWNER

**Prueba práctica: Product Owner**
**Caso de uso: Renovación de licencia de conducir**

*DIOS UNION LIBERTAD - GOBIERNO DE EL SALVADOR - PRUEBA PRÁCTICA*

---

## 1 Condiciones e instrucciones

* Puedes realizar la tarea a tu ritmo y debes entregarla en un máximo de 3 días hábiles.
* Estimamos que te tomará entre 4 y 6 horas.
* Debes enviar un PDF de hasta 10 páginas y tu prototipo. El prototipo puede estar publicado en internet, ser un archivo HTML estático o un PDF con pantallas y su flujo explicado.
* Puedes apoyarte en herramientas de IA, cualquier tipo de documentación o plantillas. Solo cuéntanos brevemente cómo las usaste y qué decisiones tomaste tú.
* No necesitas escribir código, investigar la normativa real ni hacer un diseño visual pulido.
* Después de la entrega te invitaremos a una entrevista de 45 minutos para que nos expliques tus decisiones y el prototipo.

## 2 El escenario

Una institución pública quiere que renovar la licencia de conducir sea más fácil para la ciudadanía y menos costoso para la institución. Hoy, la persona tiene que reunir documentos, hacer fila en ventanilla para pagar, revisar si tiene multas, realizar examen de visión y validar toda la información con un representante de gobierno. En algunos casos, además, debe regresar porque su solicitud tuvo observaciones. Tu trabajo es decidir qué problema resolver primero y cómo demostrar que está funcionando.

Estos son los requisitos y condiciones actuales para renovar la licencia:

1. Presentar la licencia de conducir original que se va a renovar.
2. Presentar un documento de identidad vigente, en original: los salvadoreños, su Documento Único de Identidad (DUI); los extranjeros, su carnet de residente temporal (con más de 3 meses de vigencia) o su carnet de residente definitivo.
3. Los extranjeros con visa oficial o diplomática deben presentar su carnet de identidad diplomática.
4. Aprobar el examen de agudeza visual, que tiene un costo de US$3.39 y es obligatorio en cualquier trámite de licencia.
5. Pagar el trámite, que cuesta US$52.82.
6. No tener multas pendientes de pago.
7. Si la licencia ya está vencida, pagar además una multa por renovación tardía de US$5.71.
8. La renovación se puede hacer en cualquier momento durante el mes en que vence la licencia.

**Para el ejercicio usaremos los siguientes datos ficticios:**

| Métrica | Valor |
| :--- | :--- |
| Solicitudes iniciadas al año | 240,000 |
| Duración promedio del trámite | 6 horas |
| Visitas presenciales promedio | 900 al día |
| Abandono antes de finalizar | 22% |
| Satisfacción ciudadana | 48/100 |
| Costo operativo por solicitud iniciada | US$18 |
| Solicitudes iniciadas digitalmente | 0% |

## 3 Restricciones

* La primera versión debe salir en 4 meses. Cuentas con 4 desarrolladores (2 de backend y 2 de frontend), 1 diseñador UX/UI y 1 QA, y el presupuesto es limitado.
* Los requisitos legales no se pueden cambiar por cuenta propia, y la presencialidad del ciudadano es obligatoria para el examen visual.
* Hay sistemas antiguos de otras instituciones, y poder usar sus APIs depende de acuerdos, temas de seguridad y de que estén disponibles. Como por ejemplo un sistema para validar el DUI, carnet de residente y validación de esquelas.
* La solución debe considerar la accesibilidad, a las personas con poca conectividad, la privacidad, la seguridad y la opción de recibir atención asistida.
* Puedes plantear los supuestos que consideres razonables, siempre que quede claro qué es un supuesto tuyo y qué viene de los datos entregados.

## 4 Actividades y entregables

### A. Descubrimiento y simplificación
Define cuál es el problema principal y diferencia los síntomas de sus posibles causas. Identifica tres tipos de usuarios, qué les complica el trámite y qué necesitan. Propone un plan de discovery de dos semanas y pasos que se podrían simplificar antes de pensar en digitalizarlos.

> **Qué entregar:** el problem statement, tus hipótesis ordenadas por prioridad y cómo las validarías.

### B. Estrategia y MVP
Describe la visión, la propuesta de valor y cómo se vería el trámite en el futuro. Plantea un MVP que sea realista en cuatro meses y explica qué dejarías fuera y por qué. Justifica tus decisiones de alcance y menciona las dependencias legales, operativas e institucionales. Aclara qué partes del trámite serían completamente digitales y cuáles seguirán siendo presenciales.

> **Qué entregar:** el flujo futuro, lo que entra y lo que queda fuera del alcance, y los principales riesgos.

### C. Priorización y delivery
Prioriza las siguientes iniciativas usando RICE, valor/esfuerzo o el método que prefieras. Los estimados de la tabla están en story points y son aproximados; ten en cuenta que algunas iniciativas dependen de otras.

| INICIATIVA | ESTIMADO DISEÑO (story points) | ESTIMADO BACKEND (story points) | ESTIMADO FRONTEND (story points) |
| :--- | :---: | :---: | :---: |
| Autenticación | 2 | 5 | 3 |
| Formulario de solicitud | 3 | 3 | 5 |
| Consulta de multas | 3 | 3 | 3 |
| Pago digital | 2 | 8 | 5 |
| Seguimiento del trámite | 2 | 2 | 5 |
| Notificaciones | 1 | 3 | 3 |
| Citas | 3 | 5 | 5 |
| Validación automática de documentos | 5 | 13 | 5 |
| Panel para funcionarios | 5 | 13 | 13 |

> **Qué entregar:** la matriz de priorización con tus supuestos, un plan de releases para los cuatro meses, un roadmap tentativo y dos historias de usuario con criterios de aceptación (usa el formato que más te guste y que te parezca más adecuado). Cuéntanos también cómo medirías el avance y la calidad del trabajo, más allá de las ceremonias de Scrum.

### D. Métricas y decisiones
Tres meses después del lanzamiento tienes estos resultados. Se pueden comparar con la situación inicial, pero no están separados por segmento:

| INDICADOR | ANTES | DESPUÉS |
| :--- | :--- | :--- |
| Inicio digital | 0% | 55% |
| Abandono | 22% | 38% |
| Duración | 6 horas | 3 horas |
| Satisfacción | 48/100 | 67/100 |
| Visitas presenciales promedio | 900 al día | 685 al día |
| Costo operativo | 18 USD | 18 USD |

¿Dirías que el producto fue un éxito? Propón una métrica principal enfocada en trámites resueltos y cuatro métricas que sirvan de contrapeso. Plantea posibles razones del aumento en el abandono, qué datos necesitarías para saber cuál es la correcta y un experimento con un criterio claro para decidir cuál es la causa.

### E. Prototipo de experiencia
Crea un prototipo navegable sencillo o una secuencia de pantallas con anotaciones (baja o media fidelidad). Debe mostrar el recorrido completo de una persona, de principio a fin, en al menos 5 pantallas o estados: requisitos y elegibilidad, inicio de la solicitud, ingreso o confirmación de datos, revisión y envío, y estado o resultado. Incluye al menos un error o bloqueo del que la persona pueda recuperarse (por ejemplo, una multa pendiente, un documento inválido o una falla en una integración) y una opción para recibir atención asistida.

> **Qué entregar:** un enlace a un prototipo publicado en internet (publicado en Netlify, por ejemplo), un HTML estático o un PDF con las pantallas. Anota qué se valida en cada paso, qué datos se piden y por qué, y cómo se le informa a la persona sobre el uso de sus datos y lo que sigue. Nos interesa que sea claro y fácil de usar, no que se vea bonito.

### F. Gestión de crisis
A seis semanas del lanzamiento pasa lo siguiente: la API de multas se atrasará tres meses, el área de pagos pide controles adicionales, la dirección no quiere mover la fecha, los funcionarios temen que aumente su carga de trabajo y las pruebas en móvil muestran problemas de usabilidad. ¿Qué lanzarías, qué dejarías para después, cómo lo comunicarías y qué condiciones no estarías dispuesto a negociar para salir a producción?

> **Qué entregar:** las decisiones inmediatas, un plan para las siguientes dos semanas, responsables y dependencias, criterios de go/no-go y un mensaje breve para la dirección.

## 5 Pregunta de cierre

Si en los primeros cuatro meses solo pudieras mejorar un aspecto del trámite, ¿cuál sería y cómo demostrarías que eso generó valor para la ciudadanía?

## 6 Antes de enviar, revisa que incluyas

* Un PDF de máximo 10 páginas con lo siguiente:
    * Secciones A a F
    * Tus supuestos y limitaciones bien identificados, y las fuentes externas que hayas consultado, si aplica.
* El prototipo con un PDF adjunto, con notas sobre el flujo y los casos de excepción.

Envía los archivos identificados con tu nombre y fecha a `nelson.rivera@goes.gob.sv` con copia a `reclutamiento@goes.gob.sv`