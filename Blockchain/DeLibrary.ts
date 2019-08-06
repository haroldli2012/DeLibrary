import { Contract } from 'ultrain-ts-lib/src/contract';
import { Log } from 'ultrain-ts-lib/src/log';
import { EventObject, emit } from 'ultrain-ts-lib/src/events';
import { RNAME } from 'ultrain-ts-lib/src/account';
import { Return } from 'ultrain-ts-lib/src/return';

class DeLibrary extends Contract {

  @action
  login(name: account_name): void {
    
  }

  @action
  lentReady(name: account_name, time: u32, msg: string): void {
    Log.s('name = ').s(RNAME(name)).s(' time = ').i(time, 10).s(' msg = ').s(msg).flush();
    emit('onLent', EventObject.setString('name', RNAME(name)));
    Return<string>("call lent() succeed.");
  }

  @action
  bookTrx(name: account_name): void {
    
  }

  @action
  userActivityForToken(name: account_name): void {
    
  }

  @action
  bookSwapAssurance(name: account_name): void {
    
  }

  @action
  bookSwapMatched(name: account_name): void {
    
  }

  @action
  getBookTrxHistory(name: account_name): void {
    
  }
  
}
