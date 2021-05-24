import { Component, Inject, OnInit } from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA } from '@angular/material/dialog';
import { FormBuilder, Validators } from '@angular/forms';
import { CustomValidator } from '../../utils/classes/custom-validator';
import { ConcentradosService  } from '../concentrados.service';
import { SharedService } from '../../shared/shared.service';
import { formatDate } from '@angular/common';

export interface ChecklistDialogData {
  proyecto_id?: number;
  direccion_id?: number;
  enlace?: string;
}

@Component({
  selector: 'app-dialogo-checklist-form',
  templateUrl: './dialogo-checklist-form.component.html',
  styleUrls: ['./dialogo-checklist-form.component.css']
})

export class DialogoChecklistFormComponent implements OnInit {

  constructor(
    public dialogRef: MatDialogRef<DialogoChecklistFormComponent>,
    @Inject(MAT_DIALOG_DATA) public data: ChecklistDialogData,
    private fb: FormBuilder,
    private sharedService: SharedService,
    private concentradosService: ConcentradosService,
  ) { }

  isSaving:boolean = false;
  isLoading:boolean = false;

  checklist:any;
  auditorias:any[];

  concentradoForm = this.fb.group({
    'proyecto_id':['',Validators.required],
    'direccion_id':['',Validators.required],
    'auditoria_id':['',Validators.required],
    'checklist_id':['',Validators.required],
    'enlace': ['',Validators.required],
    'fecha': ['',[Validators.required, CustomValidator.isValidDate()]]
  });

  ngOnInit() {
    this.isLoading = true;
    this.concentradosService.obtenerChecklist().subscribe(
      response => {
        console.log(response);
        if(response.error) {
          let errorMessage = response.error.message;
          this.sharedService.showSnackBar(errorMessage, null, 3000);
        } else {
          /*let checklist = response.checklist;
          for (let i in checklist.titulos) {
            let titulo = checklist.titulos[i];
            for (let j in titulo.reactivos) {
              let reactivo = titulo.reactivos[j];
              reactivo.respuesta = 1;
              reactivo.no_aplica = 1;
              reactivo.comentario = '';
            }
          }*/
          this.checklist = response.checklist;
          this.auditorias = response.auditorias;
          
          this.concentradoForm.get('proyecto_id').patchValue(this.data.proyecto_id);
          this.concentradoForm.get('direccion_id').patchValue(this.data.direccion_id);
          this.concentradoForm.get('checklist_id').patchValue(this.checklist.id);
          this.concentradoForm.get('enlace').patchValue(this.data.enlace);

          if(this.auditorias.length == 1){
            this.concentradoForm.get('auditoria_id').patchValue(this.auditorias[0].id);
          }

          let fecha_hoy = formatDate(new Date(), 'yyyy-MM-dd', 'en');
          this.concentradoForm.get('fecha').patchValue(fecha_hoy);
          //this.grupoForm.patchValue(response.data);
          //this.listaCRs = response.data.lista_c_r;
        }
        this.isLoading = false;
      },
      errorResponse =>{
        var errorMessage = "Ocurrió un error.";
        if(errorResponse.status == 409){
          errorMessage = errorResponse.error.message;
        }
        this.sharedService.showSnackBar(errorMessage, null, 3000);
        this.isLoading = false;
      }
    );
  }

  cambiarEstatusTitulo(titulo){
    for (let i in titulo.reactivos) {
      let reactivo = titulo.reactivos[i];
      reactivo.no_aplica = titulo.no_aplica;
      reactivo.respuesta = undefined;
    }
  }

  cancel(): void {
    this.dialogRef.close();
  }

  guardar(){
    if(this.concentradoForm.valid){
      let reporte_data = JSON.parse(JSON.stringify(this.concentradoForm.value));
      reporte_data.respuestas = [];

      for(let i in this.checklist.titulos){
        let titulo = this.checklist.titulos[i];
        for(let j in titulo.reactivos){
          let reactivo = titulo.reactivos[j];
          if(reactivo.respuesta > 0 || reactivo.no_aplica > 0 || reactivo.comentarios || reactivo.respuesta_id){
            reporte_data.respuestas.push({
              checklist_reactivo_id:reactivo.id,
              tiene_informacion: reactivo.respuesta,
              no_aplica: reactivo.no_aplica,
              comentarios: reactivo.comentarios
            });
          }
        }
      }
      this.isSaving = true
      
      this.concentradosService.guardarReporte(reporte_data).subscribe(
        response => {
          console.log(response);
          this.isSaving = false;
          if(response.error) {
            let errorMessage = response.error.message;
            this.sharedService.showSnackBar(errorMessage, null, 3000);
          } else {
            console.log('guardado =====================================================');
            //this.dialogRef.close(true);
          }
          //this.isLoading = false;
        },
        errorResponse =>{
          this.isSaving = false;
          var errorMessage = "Ocurrió un error.";
          if(errorResponse.status == 409){
            errorMessage = errorResponse.error.message;
          }
          this.sharedService.showSnackBar(errorMessage, null, 3000);
          //this.isLoading = false;
        }
      );
    }
  }
}
