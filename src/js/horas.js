(() => {
    const horas = document.querySelector("#horas");

    if(horas) {

       

        const categoria = document.querySelector("[name='categoria_id']");
        const dias = document.querySelectorAll("[name='dia']");
        const inputHiddenDia = document.querySelector("[name='dia_id']");
        const inputHiddenHoras = document.querySelector("[name='hora_id']");

        categoria.addEventListener("change", terminoBusqueda);
        dias.forEach(dia => dia.addEventListener("change", terminoBusqueda));


        let busqueda = {
            categoria_id: +categoria.value || "",
            dia: +inputHiddenDia.value || ""
        }

        if(!Object.values(busqueda).includes("")) {
            (async() => {
                await buscarEventos();

                const id = inputHiddenHoras.value;
                //Resaltar la hora actual
                const horaSeleccionada = document.querySelector(`[data-hora-id="${id}"]`);

                horaSeleccionada.classList.remove("horas__hora--deshabilitada");
                horaSeleccionada.classList.add("horas__hora--seleccionada");

                horaSeleccionada.onclick = seleccionarHora;
            })()
        }

        function terminoBusqueda(e) {
            busqueda[e.target.name] = e.target.value

            //Reiniciar los campos ocultos y el selector de horas
            inputHiddenHoras.value = "";
            inputHiddenDia.value = "";

            const horaPrevia = document.querySelector(".horas__hora--seleccionada");
            if(horaPrevia) {
                horaPrevia.classList.remove("horas__hora--seleccionada");
            }

            if(Object.values(busqueda).includes("")) {
                return;
            }

            buscarEventos();
        }
        
        async function buscarEventos() {
            const {dia, categoria_id} = busqueda;

                const url = `/api/eventos-horarios?categoria_id=${dia}&dia_id=${categoria_id}`
                const resultado = await fetch(url);
                const eventos = await resultado.json();

                obtenerHorasDisponibles(eventos);
        }

        function obtenerHorasDisponibles(eventos) {
            //Reiniciar la hora
            const listadoHoras = document.querySelectorAll("#horas li");
            listadoHoras.forEach(li => li.classList.add("horas__hora--deshabilitada"));

            //Comprobar eventos ya tomados, y quitar la variable de deshabilitacion
            const horasTomadas = eventos.map( evento => evento.hora_id);

            const listadoHorasArray = Array.from(listadoHoras);

            const resultado = listadoHorasArray.filter(li => !horasTomadas.includes(li.dataset.horaId))

            resultado.forEach(li => li.classList.remove("horas__hora--deshabilitada"));

            const horasDisponibles = document.querySelectorAll("#horas li:not(.horas__hora--deshabilitada)");

            horasDisponibles.forEach(horas => {
                horas.addEventListener("click", seleccionarHora);
            })
        }
        function seleccionarHora(e) {
            //Deshabilitar la hora previa si ya hay una hora seleccionada
            const horaPrevia = document.querySelector(".horas__hora--seleccionada");
            if(horaPrevia) {
                horaPrevia.classList.remove("horas__hora--seleccionada");
            }

            e.target.classList.add("horas__hora--seleccionada")

            inputHiddenHoras.value = e.target.dataset.horaId

            //Llenar el campo oculto de dia
            inputHiddenDia.value = document.querySelector('[name="dia"]:checked').value
        }
    }

})();