import { Component } from '@angular/core';
import { ApiService } from './api.service';
import { Observable, Subject, of } from 'rxjs';
import { catchError, startWith, switchMap } from 'rxjs/operators';
import { Produit } from './models/produit';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {
  title = 'tp05_tregnago_hugo';
  login: string = '';
  password: string = '';
  argsSrch: string = '';

  nom: string = '';
  prenom: string = '';
  cnx: boolean = false;
  
  searchTerms = new Subject<string>();
  produits$: Observable<Array<Produit>>;

  constructor(private apiService: ApiService) {
    this.produits$ = this.searchTerms.pipe(
      startWith(''), // Commencer avec une chaîne vide pour déclencher la récupération initiale
      switchMap((term: string) =>
        term ? this.apiService.getSearchCatalogue(term) : this.apiService.getCatalogue().pipe(
          catchError(() => of([])) // Gestion d'erreur au cas où la requête initiale échoue
        )
      )
    );
  }

  ngOnInit() {
    this.searchTerms.next(''); // Déclencher la récupération initiale au démarrage du composant
  }

  search(): void {
    this.searchTerms.next(this.argsSrch); // Mettre à jour le terme de recherche
  }
  connexion() {
    this.apiService.loginClient(this.login, this.password).subscribe((c) => {
      this.nom = c.nom;
      this.prenom = c.prenom;
      this.cnx = true;
    });
  }
}
