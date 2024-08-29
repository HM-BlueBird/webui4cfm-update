document.addEventListener("DOMContentLoaded", function () {
    const logContainer = document.getElementById("log");
    const signalContainer = document.getElementById("signal");
    const batteryContainer = document.getElementById("battery");
    const smsContainer = document.getElementById("sms");
    const radioContainer = document.getElementById("radio");
    const yacdContainer = document.getElementById("yacd");
    const execContainer = document.getElementById("execbutton");
    const execInput = document.getElementById("execinput");
    const openyacd = document.getElementById("open-yacd");
    const user = document.getElementById("user");
    
    // Mengambil data dari PHP saat tombol "Ambil Data" diklik
    setInterval(function () {
        fetch("data.php")
            .then((response) => response.json())
            .then((data) => {
                logContainer.innerHTML = JSON.stringify(data[3]).replace(/\\n/g, '').replace(/["]+/g, '');
                user.innerHTML = "User : " + JSON.stringify(data[7]).replace(/\\n/g, '').replace(/["]+/g, '');
                signalContainer.innerHTML = "Signal : " + JSON.stringify(data[0]).replace(/\\n/g, '').replace(/["]+/g, '');
                batteryContainer.innerHTML = "Battery : " + JSON.stringify(data[6]).replace(/\\n/g, '').replace(/["]+/g, '') + "%";
                const sms = data[5];
                for(let i = 0;i < sms.length - 1;i+=2){
                    if(i == 0){
                        smsContainer.innerHTML = "<tr><td>" + sms[i] + "</td></tr><tr><td> " + sms[i+1].slice(0, -1)  + "</td></tr>";
                    }else{
                        smsContainer.innerHTML += "<tr><td>" + sms[i] + "</td></tr><tr><td> " + sms[i+1].slice(0, -1)  + "</td></tr>";
                    }
                    
                }
                
                
                openyacd.setAttribute( "onClick", "location.href='http://"+data[4]+"9090/ui/#/proxies'" );
                let radio = JSON.stringify(data[1]);
                if (radio == "true"){
                    radioContainer.classList.remove('btn-dark');
                    radioContainer.classList.add('btn-danger');
                    radioContainer.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="2"></circle><path d="M16.24 7.76a6 6 0 0 1 0 8.49m-8.48-.01a6 6 0 0 1 0-8.49m11.31-2.82a10 10 0 0 1 0 14.14m-14.14 0a10 10 0 0 1 0-14.14"></path></svg>';
                }else{
                    radioContainer.classList.remove('btn-danger');
                    radioContainer.classList.add('btn-dark');
                    radioContainer.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>';
                }
                let yacd = JSON.stringify(data[2]);
                if (yacd == "true"){
                    yacdContainer.classList.remove('btn-dark');
                    yacdContainer.classList.add('btn-danger');
                    yacdContainer.value = "yacdOff";
                    yacdContainer.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" width="18" height="18" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                }else{
                    yacdContainer.classList.remove('btn-danger');
                    yacdContainer.classList.add('btn-dark');
                    yacdContainer.value = "yacdOn";
                    yacdContainer.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" width="18" height="18" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                }
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    }, 3000);
    

    // Mengirim data ke PHP saat tombol "Kirim Data" diklik
    yacdContainer.addEventListener("click", function () {
        const dataToSend = yacdContainer.value;
        fetch("data.php", {
            method: "POST",
            body: JSON.stringify({ func: dataToSend }),
            headers: {
                "Content-Type": "application/json",
            },
        })
            .then((response) => response.json())
            .then((response) => {
                console.log("Data terkirim:", response);
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    });
    radioContainer.addEventListener("click", function () {
        const dataToSend = radioContainer.value;
        fetch("data.php", {
            method: "POST",
            body: JSON.stringify({ func: dataToSend }),
            headers: {
                "Content-Type": "application/json",
            },
        })
            .then((response) => response.json())
            .then((response) => {
                console.log("Data terkirim:", response);
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    });
    execContainer.addEventListener("click", function () {
        const dataToSend = execInput.value;
        execInput.value = "";
        
        fetch("data.php", {
            method: "POST",
            body: JSON.stringify({ func: dataToSend }),
            headers: {
                "Content-Type": "application/json",
            },
        })
            .then((response) => response.json())
            .then((response) => {
                console.log("Data terkirim:", response);
                if (response == "err") {
                    execInput.placeholder = "Command Not Found";
                }else{
                    execInput.placeholder = "Command Executed";
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                execInput.placeholder = "Command Error";
            });
    });
});
