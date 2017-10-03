import { Component, OnInit, OnDestroy } from '@angular/core';
import { AppGuard } from '../../guard/app.guard';
import { Router } from '@angular/router';
import { Http } from '@angular/http';
import { Subscription } from 'rxjs/Subscription';
import { AppMailerService } from '../../services/app-mailer.service';
@Component({
  selector: 'app-newfriend',
  templateUrl: './newfriend.component.html',
  styleUrls: ['./newfriend.component.css']
})
export class NewfriendComponent implements OnInit, OnDestroy {
  // tslint:disable-next-line:no-input-rename
  friends;
  private id;
  searchedfriend;
  message: any = 'loading';
  subscription: Subscription;
  constructor(private guard: AppGuard, private router: Router, private http: Http, private messageService: AppMailerService) {
    this.subscription = this.messageService.getMessage(1).subscribe(message => {
       this.message = message; console.log(this.message);
       this.SendFriendsHtpp();
      });
  }

  ngOnInit() {
    this.id = this.guard.GetUser()['id'];
    this.SendFriendsHtpp();
  }
  ngOnDestroy() {
    // unsubscribe to ensure no memory leaks
    this.subscription.unsubscribe();
  }
  sendMessage(): void {
    // send message to subscribers via observable subject
    this.messageService.sendMessage('Something chagend In Component : newFriend', 0);
  }

  clearMessage(): void {
    // clear message
    this.messageService.clearCanal(0);
  }
  private SendFriendsHtpp() {
    this.http.post('http://127.0.0.1:8000/getnewfriends', { user: this.id }).subscribe(response => {
      this.friends = Object.values(JSON.parse(response['_body']));

    });
  }
  Onclick(id) {
    this.http.post('http://127.0.0.1:8000/addfriend', { user: id, id: this.id }).subscribe(response => {
      // tslint:disable-next-line:triple-equals
      if (response['_body'] == 'ok') {
        this.SendFriendsHtpp();
        /* to be sure that the emitted message is unique */
        // tslint:disable-next-line:prefer-const
        this.sendMessage();
        alert('Friend added :)');
      }
    });
  }
  OnKeyup(input: HTMLInputElement) {
    this.searchedfriend = '';
    // tslint:disable-next-line:triple-equals
    if (input.value != '') {
      for (let i = 0; i < this.friends.length; i++) {
        // tslint:disable-next-line:triple-equals
        if ((this.friends[i].nom.toLowerCase()).indexOf(input.value.toLowerCase()) !== -1) {
          this.searchedfriend = this.friends[i];
        }
      }
    }
  }
}
