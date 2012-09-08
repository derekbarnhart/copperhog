/**
 * Created with IntelliJ IDEA.
 * User: dbarnhart
 * Date: 8/19/12
 * Time: 1:19 PM
 * To change this template use File | Settings | File Templates.
 */

function Prize()
{
    //The prize object
    this.id = 0;
    this.description = "";
    this.redemption_code = "";
    this.details ="";
    this.raffle_id="";
    this.email="";
}

function Prize.prototype.Save()
{
    //Saves the current prize to the database


}

function Prize.prototype.getPrizes(id)
{
    //Returns an array of prize objects from the database or null
    $.().ajax(
    {
      url: "http://copperhog.nfshost.com/"

    });

}