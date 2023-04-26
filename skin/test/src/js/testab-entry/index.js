import axios from "axios";

/**
 * [%URL%] - Url страницы для отправки данных на сервер
 * [%PAGEID%] - Страница для какой отслеживаются события
 * [%GOALS_TYPE%] - Типы Goal которые необходимо увеличивать
 */
(function() {
  axios.post(
    "[%URL%]",
    {
      pageid: "[%PAGEID%]",
      goals: "[%GOALS_TYPE%]",
    },
    { headers: { "Content-Type": "multipart/form-data" } }
  );
})();
