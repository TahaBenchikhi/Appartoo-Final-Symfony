import { Injectable } from '@angular/core';
// tslint:disable-next-line:import-blacklist
import { Observable } from 'rxjs';
import { Subject } from 'rxjs/Subject';

@Injectable()
export class AppMailerService {

  private subjectList = [new Subject<any>(), new Subject<any>()];
  sendMessage(message: string, index: number) {
    this.subjectList[index].next({ text: message });
  }

  clearCanal(index: number) {
    this.subjectList[index].next();
  }

  getMessage(index: number): Observable<any> {
    return this.subjectList[index].asObservable();
  }

}
