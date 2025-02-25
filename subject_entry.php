<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>subject entry</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
    
    <form action="">
     
     <div class="row">
 
     </div>

        <div class="row">

        <div class="col-md-6 mb-3">
                            <label for="year" class="form-label">Year</label>
                            <select class="form-control" id="year" name="year" required>
                                <option value="">-- Select Year --</option>
                                <option value="1">1st Year</option>
                                <option value="2">2nd Year</option>
                                <option value="3">3rd Year</option>
                                <option value="4">4th Year</option>
                            </select>
                            <div class="invalid-feedback">Please select year.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="department" class="form-label">Department</label>
                            <select class="form-control" id="department" name="department" required>
                                <option value="">-- Select Department --</option>
                                <option value="civil">Civil Engineering</option>
                                <option value="mechanical">Mechanical Engineering</option>
                                <option value="ece">Electronics and Communication Engineering</option>
                                <option value="eee">Electrical and Electronics Engineering</option>
                                <option value="cse">Computer Science and Engineering</option>
                                <option value="it">Information Technology</option>
                                <option value="bme">Biomedical Engineering</option>
                                <option value="csbs">Computer Science and Business Systems</option>
                                <option value="ai_ds">Artificial Intelligence and Data Science</option>
                                <option value="cse_cyber">CSE (Cyber Security)</option>
                                <option value="cse_ai_ml">CSE (Artificial Intelligence and Machine Learning)</option>
                                <option value="vlsi">Electronics Engineering (VLSI Design and Technology)</option>
                                <option value="mba">Business Administration (MBA)</option>
                                <option value="mca">Computer Applications (MCA)</option>
                                <option value="sci_hum">Science and Humanities</option>
                            </select>
                            <div class="invalid-feedback">Please select department.</div>
                        </div>



        </div>



    </form>

</body>
</html>