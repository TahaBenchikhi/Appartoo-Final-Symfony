import { Component, OnInit, OnDestroy } from '@angular/core';
import { AppGuard } from '../../guard/app.guard';
import { Router } from '@angular/router';
import { Http } from '@angular/http';
import { Subscription } from 'rxjs/Subscription';
import { AppMailerService } from '../../services/app-mailer.service';
@Component({
  selector: 'app-myfriend',
  templateUrl: './myfriend.component.html',
  styleUrls: ['./myfriend.component.css']
})
export class MyfriendComponent implements OnInit, OnDestroy {

  friends;
  userid;
  message: any = 'loading';
  subscription: Subscription;
  constructor(private guard: AppGuard, private router: Router, private http: Http, private messageService: AppMailerService) {
    this.subscription = this.messageService.getMessage(0).subscribe(message => {
      
      this.message = message; console.log(this.message);
      this.getfriends();
    
    });
  }

  ngOnInit() {
    this.userid = this.guard.GetUser()['id'];
    this.getfriends();
  }
  ngOnDestroy() {
    // unsubscribe to ensure no memory leaks
    this.subscription.unsubscribe();
  }
  sendMessage(): void {
    // send message to subscribers via observable subject
    this.messageService.sendMessage('Something chagend In Component : myFriend', 1);
  }

  clearMessage(): void {
    // clear message
    this.messageService.clearCanal(1);
  }
  Onclick(id) {
    this.http.post('http://127.0.0.1:8000/removefriend', { user: id, id: this.userid }).subscribe(response => {
      // tslint:disable-next-line:triple-equals
      if (response['_body'] == 'ok') {
        this.getfriends();
        /* to be sure that the emitted message is unique */
        // tslint:disable-next-line:prefer-const
        alert('Friend Removed :)');
        this.sendMessage();
      }
    });
  }
  protected getfriends() {
    this.http.post('http://127.0.0.1:8000/getfriends', { user: this.userid }).subscribe(feedback => {
      this.friends = Object.values(JSON.parse(feedback['_body']).friends);

    });
  }
}
