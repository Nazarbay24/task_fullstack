
let data = {
    invoice_id: 1
};

fetch('/task_fullstack/api/?action=firstLoad', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify(data)
})
    .then(response => response.json())
    .then(data => {
        let table = document.getElementById("table_body");

        data.data.users.forEach(function(user) {
            table.innerHTML += "<tr class='user'>" +
                "<td>"+ user.name +"</td>" +
                "<td>"+ user.email +"</td>" +
                "<td class='to_pay'>"+ data.data.invoice.everyone_payment +"</td>" +
                "</tr>";
        });
    })
    .catch(error => {
        console.error(error.message);
    });



document.getElementById('add_user_btn').addEventListener('click', function(event) {
    event.preventDefault();

    let data = {
        name: document.getElementById('input_name').value,
        email: document.getElementById('input_email').value,
        invoice_id: 1
    };

    fetch('/task_fullstack/api/?action=addUser', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => {
            return response.json().then(data => {
                return { status: response.status, data: data };
            });
        })
        .then(data => {
            if (data.status == 200) {
                let dataT = data.data.data;
                let table = document.getElementById("table_body");
                let payments = document.querySelectorAll('.to_pay');

                payments.forEach(function(element)  {
                    element.textContent = dataT.invoice.everyone_payment;
                });

                table.innerHTML += "<tr class='user'>" +
                    "<td>"+ dataT.user.name +"</td>" +
                    "<td>"+ dataT.user.email +"</td>" +
                    "<td class='to_pay'>"+ dataT.invoice.everyone_payment +"</td>" +
                    "</tr>";
            }
            else {
                alert(data.data.message);
            }
        })
        .catch(error => {
            console.error(error.message);
        });
});



document.getElementById('reset_btn').addEventListener('click', function(event) {
    event.preventDefault();

    let data = {
        invoice_id: 1
    };

    fetch('/task_fullstack/api/?action=reset', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => {
            if (response.status == 200) {
                document.getElementById("table_body").innerHTML = "";
            }
            else {
                alert("Не удалось очистить пользователей");
            }
        })
        .catch(error => {
            console.error(error.message);
        });
});


