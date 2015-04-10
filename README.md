# NoGoodGamez
machine learning - based game recommender



1. Retrieving games:
  
  a. at first select games - i prefer to take top gross from vgchartz and form csv with em [/data/psn_gamez.csv](https://github.com/pashadude/nogoodgamez/blob/master/data/psn_gamez.csv)

  b. as we take gamedata from gamespot there might be some url-related issues not covered by function `make_gamespot_urlname` at [/app/fetcher/helpers.php](https://github.com/pashadude/nogoodgamez/blob/master/app/fetcher/helpers.php), so add those exceptions to [/data/gmz.csv](https://github.com/pashadude/nogoodgamez/blob/master/data/gmz.csv)
  
  
  c. run the [updater - /app/gamez_update.php](https://github.com/pashadude/nogoodgamez/blob/master/app/gamez_update.php)

2. Before working with PredictionIO do not forget to modify `readTraining()` in [DataSource.scala](https://github.com/pashadude/nogoodgamez/blob/master/prediction/nogoodgamezEng/src/main/scala/DataSource.scala) at you PredictionIO server for accepting likes

```scala
    val viewEventsRDD: RDD[ViewEvent] = eventsDb.find(
      appId = dsp.appId,
      entityType = Some("user"),
      eventNames = Some(List("like")),
      // targetEntityType is optional field of an event.
      targetEntityType = Some(Some("item")))(sc)
      // eventsDb.find() returns RDD[Event]
      .map { event =>
        val viewEvent = try {
          event.event match {
            case "like" => ViewEvent(
              user = event.entityId,
              item = event.targetEntityId.get,
              t = event.eventTime.getMillis)
            case _ => throw new Exception(s"Unexpected event ${event} is read.")
          }
        } catch {
          case e: Exception => {
            logger.error(s"Cannot convert ${event} to ViewEvent." +
              s" Exception: ${e}.")
            throw e
          }
        }
        viewEvent
      }.cache()

```


3.Set data at pio server with [PredictionItemsInitial.php](https://github.com/pashadude/nogoodgamez/blob/master/app/PredictionItemsInitial.php)


4.I used modified jtinder for desktop you can use original for mobile version [jTinder](https://github.com/do-web/jTinder)


5.You should update your prediction server addresses 

```php
$prophet = new PredictionController('your key',
    'http://your adress:7070',
    'http://your adress:8000');
```

at index.php, liker.php and [PredictionItemsInitial.php](https://github.com/pashadude/nogoodgamez/blob/master/app/PredictionItemsInitial.php) or simply put it to __construct at [PredictionController](https://github.com/pashadude/nogoodgamez/blob/master/app/controller/PredictionController.php)





Thank you!



