<!DOCTYPE html>
<html>
<head>
    <title>Client Digital Signatures</title>
    <script>
        function setExpiryDate() {
            var creationDates = document.getElementsByClassName("creation_date");
            var expiryDates = document.getElementsByClassName("expiry_date");


            for (var i = 0; i < creationDates.length; i++) {
                var creationDate = new Date(creationDates[i].value);
                var expiryDate = new Date(creationDate.getTime() + (2 * 365 * 24 * 60 * 60 * 1000));
                expiryDates[i].value = formatDate(expiryDate);
                expiryDates[i].readOnly = false;
            }
        }


        function formatDate(date) {
            var year = date.getFullYear();
            var month = ("0" + (date.getMonth() + 1)).slice(-2);
            var day = ("0" + date.getDate()).slice(-2);
            return year + "-" + month + "-" + day;
        }
    </script>
</head>
<body>
    

    <h1>Client Digital Signatures</h1>


        <label>Signature Types:</label><br>
        <label><input type="checkbox" class="signature_type" name="signature1" value="Type 1"> Type 1</label><br>
        <label><input type="checkbox" class="signature_type" name="signature2" value="Type 2"> Type 2</label><br>
        <label><input type="checkbox" class="signature_type" name="signature3" value="Type 3"> Type 3</label><br><br>


        <div id="date_fields_container"></div><br>


        <script>
            var signatureTypes = document.getElementsByClassName("signature_type");
            var dateFieldsContainer = document.getElementById("date_fields_container");


            for (var i = 0; i < signatureTypes.length; i++) {
                signatureTypes[i].addEventListener("change", function () {
                    dateFieldsContainer.innerHTML = "";
                    for (var j = 0; j < signatureTypes.length; j++) {
                        if (signatureTypes[j].checked) {
                            var dateLabel = document.createElement("label");
                            dateLabel.innerText = "Creation Date (" + signatureTypes[j].value + "):";


                            var creationDateField = document.createElement("input");
                            creationDateField.type = "date";
                            creationDateField.name = "creation_date[]";
                            creationDateField.className = "creation_date";
                            creationDateField.addEventListener("change", setExpiryDate);


                            var expiryLabel = document.createElement("label");
                            expiryLabel.innerText = "Expiry Date (" + signatureTypes[j].value + "):";


                            var expiryDateField = document.createElement("input");
                            expiryDateField.type = "date";
                            expiryDateField.name = "expiry_date[]";
                            expiryDateField.className = "expiry_date";
                            expiryDateField.readOnly = true;


                            dateFieldsContainer.appendChild(dateLabel);
                            dateFieldsContainer.appendChild(creationDateField);
                            dateFieldsContainer.appendChild(document.createElement("br"));
                            dateFieldsContainer.appendChild(expiryLabel);
                            dateFieldsContainer.appendChild(expiryDateField);
                            dateFieldsContainer.appendChild(document.createElement("br"));
                            dateFieldsContainer.appendChild(document.createElement("br"));
                        }
                    }
                });
            }
        </script>
    </form>
</body>
</html>