// Manejar envío del formulario (actualizado para agregar visualmente)
document.getElementById('lead-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = {
        nombre: document.getElementById('nombre').value,
        correo: document.getElementById('correo').value,
        numero_telefono: document.getElementById('numero_telefono').value,
        fecha_creado: document.getElementById('fecha_creado').value,
        id_etapa: document.getElementById('id_etapa').value
    };

    fetch('/api/leads', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${TOKEN}`
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        console.log('Success:', data);

        // Agregar el nuevo lead visualmente como PRIMER hijo
        const etapaContainer = document.getElementById(`leads-etapa-${data.lead.id_etapa}`);
        if (etapaContainer) {
            const leadElement = crearElementoLead(data.lead);
            etapaContainer.prepend(leadElement); // ← Aquí el cambio
        }

        mostrarNotificacion('Lead creado exitosamente!', 'success');
        this.reset();
    })
    .catch(error => {
        console.error('Error:', error);
        mostrarNotificacion('Error al crear lead', 'error');
    });
});
