import "allocator/arena";
import { Contract } from "ultrain-ts-lib/src/contract";
import { Log } from "ultrain-ts-lib/src/log";
import { NAME, Account } from "ultrain-ts-lib/src/account";

class Library implements Serializable {
  @primaryid
  id  : u64;
  title: string;
  price: u32;
  isbn: string;
  @ignore
  author: u32;

  constructor(_id: u64 = 0, _name: string = "unknown") {
    // do something
  }

  prints(): void {
    Log.s("id = ").i(this.id).s(", title = ").s(this.title).flush();
  }
}

const lenttable = "tb.lent";
const swappingtable = "tb.swapping";

@database(Library, "tb.lent")
@database(Library, "tb.swapping")
class HumanResource extends Contract {

  lentdb: DBManager<Library>;
  swappingdb: DBManager<Library>;

  constructor(code: u64) {
    super(code);
    this.lentdb = new DBManager<Library>(NAME(lenttable), NAME(lenttable));
    this.swappingdb = new DBManager<Library>(NAME(swappingtable), NAME(swappingtable));
  }

  @action
  addlent(id: u64, title: string, price: u32, isbn: string, street: string, post: string, author: u32): void {
    let p = new Library();
    p.id = id;
    p.title = title;
    p.price = price;
    p.author = author;

    let existing = this.lentdb.exists(id);
    ultrain_assert(!existing, "this Library has existed in db yet.");
    this.lentdb.emplace(p);
  }

  @action
  addswapping(id: u64, title: string, price: u32, isbn: string, street: string, post: string, author: u32): void {
    let p = new Library();
    p.id = id;
    p.title = title;
    p.price = price;
    p.author = author;

    let existing = this.swappingdb.exists(id);
    ultrain_assert(!existing, "this Library has existed in db yet.");
    this.swappingdb.emplace(p);
  }

  @action
  modify(id: u64, title: string, author: u32): void {
    let p = new Library();
    let existing = this.lentdb.get(id, p);
    ultrain_assert(existing, "the Library does not exist.");

    p.title   = title;
    p.author = author;

    this.lentdb.modify(p);
  }

  @action
  remove(id: u64): void {
    Log.s("start to remove: ").i(id).flush();
    let existing = this.lentdb.exists(id);
    ultrain_assert(existing, "this id is not exist.");
    this.lentdb.erase(id);
  }

  @action
  enumrate(dbname: string): void {
    let cursor: Cursor<Library> = new Cursor<Library>();
    if (dbname == "lent") {
      cursor = this.lentdb.cursor();
    } else if (dbname == "swapping") {
      cursor = this.swappingdb.cursor();
    } else {
      ultrain_assert(false, "unknown db title.");
    }
    Log.s("cursor.count =").i(cursor.count).flush();

    while(cursor.hasNext()) {
      let p = cursor.get();
      p.prints();
      cursor.next();
    }
  }

  @action
  drop(dbname: string): void {
    if (dbname == "lent") {
      this.lentdb.dropAll();
    } else if (dbname == "swapping") {
      this.swappingdb.dropAll();
    } else {
      ultrain_assert(false, "unknown db title.");
    }
  }


  @action
  pubkeyOf(account: account_name): void {
    let key = Account.publicKeyOf(account, 'wif');
    Log.s("public key with WIF is : " ).s(key).flush();
    key = Account.publicKeyOf(account, 'hex');
    Log.s("public key with HEX is : " ).s(key).flush();

  }
}