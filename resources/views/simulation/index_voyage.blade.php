@extends('layouts.master')
@section('title', 'Step Form Wizard')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/cdn_css_intel.css')}}">
<link rel="stylesheet" href="styles.css" />
<style>
    .error{
    border:2px solid red;
    }
    .success{
    border:2px solid green;
    }
</style>
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Simulateur voyage</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Simulateur</li>
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">
					<h5>Renseigner vos informations</h5>
				</div>
				<div class="card-body">
					<form action="{{route('SimulerVoyage')}}" class="form-controller" method="post">
                    {{ csrf_field()}}
                        <div class="row">
                            <h4>Déstination :</h4>
                            <div class="col-md-6">
                                <label  class="form-label" >Pays d'origine</label>
                                <select  name="" class="form-select" required>
                                    <option selected="" value="senegal">Sénégal</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label  class="form-label" >Pays déstination</label>
                                    <select  name="code_pays" class="form-select" required>
                                        @foreach ($codepays as $data)
                                            <option value="{{ $data->Code}}" >{{ $data->Nom }} </option>
                                        @endforeach
                                    </select>
                            </div>
                        </div><br>
                            <h4>Durée :</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="date-picker">
                                    <label class="form-label">Date départ</label>
                                    <input class="form-control" id="date_depart" name="date_depart" type="date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="date-picker">
                                    <label class="form-label">Date retour</label>
                                    <input class="form-control" id="date_retour" name="date_retour" type="date" required>
                                </div>
                            </div><br>
                                
                                    
                        </div><br>
                            <h4>Passager :</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="date-picker">
                                    <label class="control-label">Date de naissance</label>
                                    <input class="form-control" id="date_naissance" name="date_naissance" type="date" required>        
                                </div>
                            </div>
                        <div class="col-md-6">
                            <label class="col-form-label">Téléphone</label></br>
                            <input id="phone" class="form-control" name="contact" type="tel"  required>
                            <!-- <input type="button" class="btn btn-warning" value="verifier" /> -->
                           
                            </div>
                        
                        </div><br>
                        <div >
                        <button class="btn btn-primary" type="button" id="validate-button">Simuler</button>
                        <button type="submit" style="display:none;" id="hidden-submit">Submit</button>
                            <!-- <button class="btn btn-primary" type="submit">Simuler</button> -->
                        </div>
                    </form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script src="{{asset('assets/js/form-wizard/form-wizard-three.js')}}"></script>
<script src="{{asset('assets/js/form-wizard/jquery.backstretch.min.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.fr.js')}}"></script>
<script src="{{asset('assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    // Définir la date d'aujourd'hui et celle de demain
    const today = new Date().toISOString().split("T")[0];
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const tomorrowFormatted = tomorrow.toISOString().split("T")[0];
    // Définir les valeurs minimales
    document.getElementById('date_depart').min = today;
    document.getElementById('date_retour').min = tomorrowFormatted;
    document.getElementById('date_naissance').max = today;

    // Validation pour la date de départ
    document.getElementById('date_depart').addEventListener('input', function() {
        const dateDepart = this.value;
        const dateRetourField = document.getElementById('date_retour');

        // Met à jour la date minimale pour la date de retour en fonction de la date de départ
        if (dateDepart) {
            const departDate = new Date(dateDepart);
            departDate.setDate(departDate.getDate() + 1);
            dateRetourField.min = departDate.toISOString().split("T")[0];
        }

        // if (dateDepart < today) {
        //     this.value = ''; // Réinitialise la valeur si la date est inférieure à aujourd'hui
        //     alert('La date de départ ne peut pas être inférieure à la date du jour.');
        //     console.log('La date: '.dateDepart);
        // }
    });

    // Validation pour la date de retour
    document.getElementById('date_retour').addEventListener('input', function() {
        const dateDepart = document.getElementById('date_depart').value;
        const dateRetour = this.value;

        // if (dateRetour < tomorrowFormatted) {
        //     this.value = ''; // Réinitialise la valeur si la date est inférieure à demain
        //     alert('La date de retour ne peut pas être inférieure à demain.');
        // }

        // if (dateRetour < dateDepart) {
        //     this.value = ''; // Réinitialise la valeur si la date de retour est antérieure à la date de départ
        //     alert('La date de retour ne peut pas être antérieure à la date de départ.');
        // }
    });

    // Validation pour la date de naissance
    document.getElementById('date_naissance').addEventListener('input', function() {
        const dateNaissance = this.value;

        if (dateNaissance > today) {
            this.value = ''; // Réinitialise la valeur si la date est supérieure à aujourd'hui
            alert('La date de naissance ne peut pas être supérieure à la date du jour.');
        }
    });
</script> 


@endsection